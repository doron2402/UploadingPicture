<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
    <script src="http://malsup.github.com/jquery.form.js"></script> 

<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview").html('');
			    $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#preview'
		}).submit();
		
			});
        }); 
</script>

<style>
.preview
{
width:200px;
border:solid 1px #dedede;
padding:10px;
}
#preview
{
color:#cc0000;
font-size:12px
}

</style>
<body>


<div style="width:600px">

<form id="imageform" method="post" enctype="multipart/form-data" action='index.php?r=site/AjaxImg'>
Upload your image <input type="file" name="photoimg" id="photoimg" />
</form>
<div id='preview'>
</div>


</div>
