<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Images';
$this->breadcrumbs=array(
	'Images',
);
?>

<h1>Images</h1>
<script type="text/javascript">
function ShowEmailPopUp(Src)
{
	$('#EmailPopUp').show();
        $('input[name$="Src"]').val('');
	$('input[name$="Src"]').val(Src);
}
function SendEmail()
{
	var Email =$('input[name$="Email"]').val();
	var Src =$('input[name$="Src"]').val();
	var Data = "Email=" + Email + "&Src=" + Src;
        $.ajax({
        url: 'index.php?r=site/SendEmailPic',
        type: 'POST',
        data: Data,
        success: function(ret_data) {
         $('#EmailPopUp').hide();
         alert(ret_data);
        }
      });	
}
</script>
<div id="EmailPopUp" style="padding:20px;display:none;position:absolute;background-color:navy; width:300px;height:100px;margin-left:300px;">
    <a onClick="$('#EmailPopUp').fadeOut('slow');">Close</a>
    <br />
        <input type="text" name="Email" id="Email" />
	<input type="hidden" value="" name="Src" id="Src"/>
	<a onClick="SendEmail();" style="cursor:pointer;">Send Link</a>
</div>
<div id="Images">
<?php
foreach($Img_arr as $Img)
{
	echo '<a style="cursor:pointer;" onClick="ShowEmailPopUp(\''.$Img.'\');"><img src="'.$Img.'" width="100" /></a>';
}
?>
</div>