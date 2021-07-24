<?php

	/*
	 *	control_upload_file.php
	 *	文件上传模块
	 *
	 *	源码转自菜鸟教程，有改动。
	 *
	 *	此模块调用方式：使用 iframe 标签加载此页面，
	 *	并使用 session 变量指定参数。
	 *
	 */
	 
	session_start();
	
	/*
	 *	$_SESSION 变量
	 *		control_upload_file_exts
	 *			字符串数组，存储所有支持上传的文件的扩展名，如果数组的第一项是 '*' 则表示支持所有文件
	 *		control_upload_file_max_size
	 *			指定上传的文件最大大小，单位须指定
	 *		control_upload_file_size_unit
	 *			指定 control_upload_file_max_size 的单位，可以是 "byte","kb","mb","gb" 中的其一，不区分大小写。
	 *		control_upload_file_save_path
	 *			指定上传的文件的存储路径。注意：如果是相对路径，则是相对于此文件而言的。
	 *		control_upload_file_allow_repeat
	 *			指定是否允许上传重复名称的文件，如果允许则会将重复的名称进行修改，否则会输出错误。此变量只允许布尔值。
	 *		control_upload_file_name
	 *			存储上传的文件的名称
	 *		control_upload_file_type
	 *			存储上传的文件的类型
	 *		control_upload_file_size
	 *			存储上传的文件的大小，单位为字节
	 *		control_upload_file_temp_path
	 *			存储上传的文件被临时存储的位置
	 *		control_upload_file_state
	 *			存储当前上传文件的状态
	 *			0 - 没有收到文件上传指令
	 *			1 - 文件成功上传
	 *			2 - 文件上传失败，后缀名不符合要求
	 *			3 - 文件上传失败，文件大小超过限制
	 *			4 - 重复的文件名（如果不允许上传重复名称的文件）
	 *			文件上传中遇到错误：此时变量存储的是错误信息字符串。
	 *			此变量默认值为 0，在第一次上传文件之后就会发生更改，不会自动归零，需要手动修改。
	 */

?>

<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<form method="post" enctype="multipart/form-data">
			<label for="file">要上传的文件：</label>
			<input type="file" name="file" id="file"></input>
			<br/>
			<button name="button" value="submit"> 提交（提交后等待提示上传完成） </button>
		</form>
	</body>
</html>

<?php
	
	// 未提交表单
	if($_POST['button'] != "submit")
	{
		return;
	}
	
	// 未选择文件
	if(empty($_FILES["file"]["name"]))
	{
		echo "<script>alert('请先选择文件。');</script>";
		return;
	}

	$allowedExts = $_SESSION['control_upload_file_exts'];
	$max_size = $_SESSION['control_upload_file_max_size'];
	$unit = strtolower($_SESSION['control_upload_file_size_unit']); 
	$path = $_SESSION['control_upload_file_save_path'];

	// 单位转换
	switch($unit)
	{
	case "kb": $max_size = $max_size * 1024;		break;
	case "mb": $max_size = $max_size * 1048576;		break;
	case "gb": $max_size = $max_size * 1073741824;	break;
	}

	// 获取文件后缀名
	$extension = end(explode(".", $_FILES["file"]["name"]));

	// 后缀名不符合要求
	if($allowedExts[0] != "*" && !in_array($extension, $allowedExts))
	{
		$_SESSION['control_upload_file_state'] = 2;
		return;
	}
	// 文件大小超过限制
	if($_FILES["file"]["size"] > $max_size)
	{
		$_SESSION['control_upload_file_state'] = 3;
		return;
	}
	// 文件上传遇到错误
	if ($_FILES["file"]["error"] > 0)
	{
		$_SESSION['control_upload_file_state'] = $_FILES["file"]["error"];
		return;
	}
	
	$_SESSION['control_upload_file_type'] = $_FILES["file"]["type"];
	$_SESSION['control_upload_file_size'] = $_FILES["file"]["size"];
	$_SESSION['control_upload_file_temp_path'] = $_FILES["file"]["tmp_name"];

	// 转换编码
	$file_name_gbk = iconv("UTF-8", "GB2312", $_FILES["file"]["name"]);
	$_SESSION['control_upload_file_save_path'] = iconv("UTF-8", "GB2312", $_SESSION['control_upload_file_save_path']);

	// 若文件名重复，则修改到不重复为止。
	while (file_exists($_SESSION['control_upload_file_save_path'].$file_name_gbk))
	{
		if(!$_SESSION['control_upload_file_allow_repeat'])
		{
			$_SESSION['control_upload_file_state'] = 4;
			return;
		}
		
		$file_name_gbk = substr_replace($file_name_gbk,"_(repeat)",strrpos($file_name_gbk,"."),0);
	}
	
	// 转换编码
	$file_name_utf8 = iconv("gbk", "utf-8", $file_name_gbk);
	$_SESSION['control_upload_file_name'] = $file_name_utf8;
	
	// 上传文件
	move_uploaded_file($_FILES["file"]["tmp_name"], $_SESSION['control_upload_file_save_path'].$file_name_gbk);
	$_SESSION['control_upload_file_state'] = 1;
	
	echo "<script>alert('您上传的文件：\\n文件名：{$file_name_utf8}\\n大小：".($_FILES["file"]["size"] / 1048576)." MB\\n\\n文件上传完成！~~~');</script>";
?>