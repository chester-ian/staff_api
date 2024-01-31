<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Staff;
use Swagger\Annotations as SWG;

class APIController extends AbstractController
{
     
    #TODO: Use attributes to put details on the Open API responses
    #Documentation : https://symfony.com/bundles/NelmioApiDocBundle/current/index.html#general-php-objects


    #[Route('/api/staff/{id}', methods: ['GET'], name: 'get_staff')]
    public function getStaff(EntityManagerInterface $em,$id=0): JsonResponse
    {
        //authentication
        if (!$this->authenticate()){ 
            $message = ['status' => 'error','msg' => 'authentication error',];
            $response_msg = new JsonResponse($message);
            $response_msg->setStatusCode(403);
            return $response_msg;
        }

        //check id if integer
        if (!filter_var($id, FILTER_VALIDATE_INT) !== false && $id<>0) {
            $message = ['status' => 'error','msg' => 'Id is not an integer',];
            $response_msg = new JsonResponse($message);
            $response_msg->setStatusCode(400);
            return $response_msg;   
        }

        //proceed if valid id
        try {
            $data=new JsonResponse($id);
            if ($id > 0){
                $result = $em->getRepository(Staff::class)->getStaff($id);
                if ($result==null){
                    $message = ['status' => 'message','msg' => 'No Record Found',];
                    $response_msg = new JsonResponse($message);
                    return $response_msg;    
                }

                $data = new JsonResponse($result);
            }
            else {
                $result = $em->getRepository(Staff::class)->getStaff(0);
                if ($result==null){
                    $message = ['status' => 'message','msg' => 'No Record Found',];
                    $response_msg = new JsonResponse($message);
                    return $response_msg;    
                }
                $data = new JsonResponse($result);
            }
            
        }
        catch(\Exception $e){
            return new JsonResponse($e->getMessage());
        }
        return $data;
    }

    #[Route('/api/staff', methods: ['POST'], name: 'add_staff')]
    public function addStaff(EntityManagerInterface $em, Request $request,$id=0): JsonResponse
    {
        //authentication
        if (!$this->authenticate()){ 
            $message = '[{"status":"error", "msg":"authentication error"}]';
            $response_msg = new JsonResponse($message);
            $response_msg->setStatusCode(403);
            return $response_msg;
        }
        $content = $request->getContent();
        //check if its a valid json
        if (json_decode($content) == null) {
            $message = ['status' => 'message','msg' => 'Body is not a JSON',];
            $response_msg = new JsonResponse($message);
            return $response_msg; 
        }
        $objStaff = json_decode($content,true);
        //validation of critical content body fields
        $res = $this->validateContent($em, "POST", $objStaff);
        if ($res != 1){
            $message = ['status' => 'message','msg' => $res,];
            $response_msg = new JsonResponse($message);
            return $response_msg;     
        }
        try{
            
            if (is_numeric($em->getRepository(Staff::class)->addStaff($objStaff[0]))){
                $message = ['status' => 'message','msg' => 'Record Has Been Successfully Added',];
                $response_msg = new JsonResponse($message);
                return $response_msg;
            }
        }
        catch(\Exception $e){
            $message = ['status' => 'message','msg' => 'There was an error in adding a record.',];
            return new JsonResponse($message);
        }
    }

    #[Route('/api/staff/{id}', methods: ['PUT'], name: 'update_staff')]
    public function updateStaff(EntityManagerInterface $em, Request $request,$id=0): JsonResponse
    {
        //authentication
        if (!$this->authenticate()){ 
            $message = ['status' => 'error','msg' => 'authentication error',];
            $response_msg = new JsonResponse($message);
            $response_msg->setStatusCode(403);
            return $response_msg;
        }
        $content = $request->getContent();
        //check if its a valid json
        if (json_decode($content) == null) {
            $message = ['status' => 'message','msg' => 'Body is not a JSON',];
            $response_msg = new JsonResponse($message);
            return $response_msg; 
        }
        $objStaff = json_decode($content,true);
        //validation of critical content body fields
        $res = $this->validateContent($em, "PUT", $objStaff, $id);
        if ($res != 1){
            $message = ['status' => 'message','msg' => $res,];
            $response_msg = new JsonResponse($message);
            return $response_msg;     
        }

        try{
            if (is_numeric($em->getRepository(Staff::class)->updateStaff($id,$objStaff[0]))){
                $message = ['status' => 'message','msg' => 'Record Has Been Successfully Updated',];
                $response_msg = new JsonResponse($message);
                return $response_msg;
            }
        }
        catch(\Exception $e){
                $message = ['status' => 'message','msg' => 'There was an error in updating a record.',];
                return new JsonResponse($message);
        }
    }

    #[Route('/api/staff/{id}', methods: ['DELETE'], name: 'delete_staff')]
    public function deleteStaff(EntityManagerInterface $em, Request $request,$id=0): JsonResponse
    {
        //authentication
        if (!$this->authenticate()){ 
            $message = ['status' => 'error','msg' => 'authentication error',];
            $response_msg = new JsonResponse($message);
            $response_msg->setStatusCode(403);
            return $response_msg;
        }
        $objStaff=null;
        //validation of critical content body fields
        $res = $this->validateContent($em, "DELETE", $objStaff, $id);
        if ($res != 1){
            $message = ['status' => 'message','msg' => $res,];
            $response_msg = new JsonResponse($message);
            return $response_msg;     
        }

        try{
            $result = $em->getRepository(Staff::class)->deleteStaff($id,$objStaff);
            if (is_numeric($result)){
                $message = ['status' => 'message','msg' => 'Record Has Been Successfully Deleted',];
                $response_msg = new JsonResponse($message);
                return $response_msg;
            }
            
        }
        catch(\Exception $e){
            $message = ['status' => 'message','msg' => 'There was an error in deleting a record.',];
            return new JsonResponse($message);
        }

    }

    #TODO:
    #validate content before pursuing db access
    #isolate this from the controller and suggested to be in the helper class
    #Sanitise string / text inputs to not have <script> tags as well.
    #Refactor repeated lines of code
    private function validateContent($em,$method,$objStaff,$id=0) : int | string
    {
        $validSquadOptions = array("SQUAD1","SQUAD2","SQUAD3","SQUAD4","SQUAD5","NA");
        $validStatusOptions = array("ACTIVE","INACTIVE");
        switch($method) {
            case "POST":
                if (!$em->getRepository(Staff::class)->isValidEmail($objStaff[0]['email'])){
                    return "Email is not valid";
                }
                if (!in_array(strtoupper($objStaff[0]['status']),$validStatusOptions)){
                    return "Status is not valid";
                }
                if (!in_array(strtoupper($objStaff[0]['squad']),$validSquadOptions)){
                    return "Squad is not valid";
                }
                return 1;
                break;
            case "PUT":
                if (!$em->getRepository(Staff::class)->isValidId($id)){
                    return "Id is not valid";
                }
                if (!in_array(strtoupper($objStaff[0]['status']),$validStatusOptions)){
                    return "Status is not valid";
                }
                if (!in_array(strtoupper($objStaff[0]['squad']),$validSquadOptions)){
                    return "Squad is not valid";
                }
                break;
            case "DELETE":
                if (!$em->getRepository(Staff::class)->isValidId($id)){
                    return "Id is not valid";
                }
                break;
        }
        return true;
    }

    
    #TODO:
    #authenticate should not be in a separate helper class
    private function authenticate(){
        return true;
    }

}
