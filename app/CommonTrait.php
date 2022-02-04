<?php

namespace App;
use Image;
use App\Models\EmailTemplate;
use App\Models\EmailList;
use JD\Cloudder\Facades\Cloudder;
use GuzzleHttp\Client;
use GuzzleHttp;
use Twilio\Rest\Client as TwilloClient;
use App\Models\SmsTemplate;
use App\Models\CMSPages;

use Mail;

trait CommonTrait{
	

    /**
     * Send Bulk Email Using SMTP
     * @param  $params
     * @return $Response
    */
    public static function sendBulkEmailUsingSMTP($params = array())
    {
      $is_email_sent = 0;
      if(count($params) > 0){
    
      $email_name = $params['email_name'];
      $to_email = $params['to_email'];
      $to_name = isset($params['to_name'])? $params['to_name'] : '';
      $subject = isset($params['subject'])? $params['subject'] : '';
      
      $emailListObj = EmailList::findByName($email_name);
      if(isset($emailListObj->id) && $emailListObj->id > 0){
          
          $subject = (trim($subject) != "")? $subject : $emailListObj->subject;
          $subject = isset($emailListObj->subject) && $emailListObj->subject != "" ? $emailListObj->subject : $subject;
          $email_text = stripslashes($emailListObj->email_text);
          
          
          if (count($params) > 0) {
              foreach ($params as $key => $val) {
                  $email_text = str_replace('{' . strtolower($key) . '}', $val, $email_text);
                  $email_text = str_replace('{' . strtoupper($key) . '}', $val, $email_text);
              }
          }
          
          $emailTemplateObj = EmailTemplate::find($emailListObj->template_id);
          
          $main_body = stripslashes($emailTemplateObj->html);
          $body = str_replace('{CONTENT}', $email_text, $main_body);
          $body = html_entity_decode($body);
          
          $from_name = 'Admin';
          $from_email = ADMIN_EMAIL;              
                  
          $data_to_insert = array(
            'to_email' => $to_email,
            'from_email' => $from_email,
            'subject' => $subject,
            'html' => addslashes($body),                        
            'status' => '1',                        
          );
          \App\Models\EmailLog::create($data_to_insert);
        
          Mail::send('emails.send', ['body' => $body], function ($m) use ($body, $from_name, $from_email, $to_email, $to_name, $subject) {
          	 $m->from($from_email, $from_name);
          	 $m->to($to_email, $to_name)->subject($subject);
          });
      }
		}
		return $is_email_sent;
	}


    /**
     * Generate the random string
     * @param  $length
     * @param  $type - 'str' for any string, 'int' for digits.
     * @return $Response
    */
    public static function randomString($length = 40,$type = 'str') {
        
        $characters = $type == 'int' ? '0123456789' : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+=';
        $randomString = '';
      
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }



    /**
     * Delete directory Function 
     * @param  $dir - path of directory want to delete.
     * @return $Response
    */
    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
            continue;
            }
            if (!CommonTrait::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
            }
        }
        return rmdir($dir);
    }
    
    
    /**
     * Generate the random string
     * @param  $fullpath
     * @param  $uploaded_filename.
     * @return $Response
    */
    public static function getFilename($fullpath, $uploaded_filename)
    {
        $count = 1;
        $new_filename = $uploaded_filename;
        $firstinfo = pathinfo($fullpath);
    
        while (file_exists($fullpath)) {
          $info = pathinfo($fullpath);
          $count++;
          $new_filename = $firstinfo['filename'] . '(' . $count . ')' . '.' . $info['extension'];
          $fullpath = $info['dirname'] . '/' . $new_filename;    
        }

        return $new_filename;
    }
    

}	

