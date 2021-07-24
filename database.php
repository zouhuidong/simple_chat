<?php
	/*
	 *	数据库连接
	 */

	$g_dbSevername;			// 服务器名称
	$g_dbUsername;			// 服务器用户名
	$g_dbPassword;			// 服务器密码
	$g_dbDatabase;			// 数据库名称
	$g_dbServerTimeZone;	// 数据库时区
	$g_dbConnect;			// 连接到数据库的返回值
	$g_fpUpdateHistory;		// 更新日志的文件路径
	
	$g_dbSevername = "localhost";
	$g_dbUsername = "root";	
	$g_dbPassword = "";
	$g_dbDatabase = "simple_chat";
	$g_dbServerTimeZone = "PRC";

	// 设置默认时区
	date_default_timezone_set($g_dbServerTimeZone);
	
	// 创建和服务器的连接
	$g_dbConnect = mysqli_connect($g_dbSevername, $g_dbUsername, $g_dbPassword);

	// 检测连接
	if (!$g_dbConnect) {
		echo "服务器连接失败: ".mysqli_connect_error();
		//return;
	}
	else
	{
		// 连接数据库
		if (!mysqli_select_db($g_dbConnect,$g_dbDatabase))
		{
			echo '<br/>不能选择到数据库： '.$g_dbDatabase.'<br>';
			//return;
		}
	}
?>