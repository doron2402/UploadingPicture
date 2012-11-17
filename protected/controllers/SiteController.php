<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/*
		Ajax Image uploader
		UploadPic2/index.php?r=site/AjaxImg
	*/
	public function actionAjaxImg()
	{
		
		$path = "uploads/";

	$valid_formats = array("jpg", "png", "gif", "bmp");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*1024))
						{
							$actual_image_name = md5(time()).".".$ext;
							$tmp = $_FILES['photoimg']['tmp_name'];
							if(move_uploaded_file($tmp, $path.$actual_image_name))
								{									
									$Src = $path.$actual_image_name;
									//Insert into the DB the image itself
									$connection=Yii::app()->db;
									$sql="INSERT INTO `doron`.`images` (`Id`, `Src`) VALUES (NULL, '".$Src."');";
									$dataInsert =$connection->createCommand($sql)->query();
									//var_dump($dataInsert);
									//Showing the Client the image that he uploaded
									echo '<script>alert("'.$Src.'");</script>';
									echo "<img src='uploads/".$actual_image_name."'  class='preview'>";
								}
							else
								echo "failed";
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
	}//End of AjaxImg method
	
	/*
		Images Page
	*/
	public function actionImages()
	{
		$images = Yii::app()->db->createCommand()->select('Src')->from('images')->queryAll();
		//var_dump($images);
		$i = 0;
		while (isset($images[$i]['Src']))
		{
			$Img_arr[$i]= $images[$i]['Src'];
			$i++;
		}
		$this->render('images',array("Img_arr" => $Img_arr));
	}
	
	/*
         * 
         * 
         */
	public function actionSendEmailPic()
        {
            $Email = $_POST['Email'];
            $Src = $_POST['Src'];
            $Msg = 'Hi, got new photo from your friend <a href="http://localhost/UploadPic2/index.php?r=site/LinkImage&Photo='.$Src.'" />Press Here</a>';
            $S = mail($Email,'Got new Photo',$Msg);
            if($S)
            {
                echo 'Email Sent!';
            }
            else
            {
                echo 'Could not send email';
            }
            
        }
        
        /*
         * Show image from email
         */
        public function actionLinkImage()
        {
            $ImageSrc = $_GET['Photo'];
            $this->render('linkimage',array('ImageSrc' => $ImageSrc));
            
        }

                /**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}