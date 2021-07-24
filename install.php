<?php

	include_once("database.php");
	
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>安装和卸载数据库</title>
	</head>
	
	<body>
	
		<h1 align="center"><font color="blue">安装和卸载数据库</font></h1>
		<hr>
		<table>
			<tr>
				<td><a href="?step=2" target="_self">安装数据库</a></td>
			</tr>
			<tr>
				<td><a href="?step=3" target="_self">删除数据库</a></td>
			</tr>
			
		</table>
		<hr>
		
		page created by huidong
		<br/>
		
	</body>
</html>

<?php


	/*
	 *	数据库操作
	 */
	 
	// 创建表
	function CreateTables()
	{
		// 得到全局变量
		$g_dbConnect = $GLOBALS['g_dbConnect'];
		
		echo "<br/>开始创建表<br/>";
		
		$sql = "CREATE TABLE `message` (
			`id` int(4) unsigned NOT NULL auto_increment,
			`time` datetime,
			`name` text,
			`text` text,
			`ip` text,
			`location` text,
			PRIMARY KEY (`id`)
		)";
		if(mysqli_query($g_dbConnect,$sql))
		{
			echo "<br/>Create table Successfully";
		}
		else
		{
			echo "<br/>Error Create table:".mysqli_error($g_dbConnect);
		}
	}
	
	// 创建数据库，若已存在则创建
	function CreateDatabase()
	{
		// 得到全局变量
		$g_dbConnect = $GLOBALS['g_dbConnect'];
		$g_dbDatabase = $GLOBALS['g_dbDatabase'];
		
		//连接数据库
		if (!mysqli_select_db($g_dbConnect,$g_dbDatabase))
		{
			//连接数据库失败
			echo '<br>could not select database '.$g_dbDatabase;
			echo '<br>create database '.$g_dbDatabase;
			$sql = 'CREATE DATABASE IF NOT EXISTS '.$g_dbDatabase.' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;';
			if (mysqli_query($g_dbConnect,$sql))
			{
				echo "<br>Database ".$g_dbDatabase." created successfully";
			}
			else
			{
				echo '<br>Error creating database: ' . mysqli_error($g_dbConnect);
			}

			mysqli_select_db($g_dbConnect,$g_dbDatabase);
		}
		
		echo "<br/>连接数据库成功。<br/>";
	}

	
	/*
	 *	用户操作
	 */
	
	// 检测连接
	if (!$g_dbConnect) {
		die("Connection failed: " . mysqli_connect_error());
	}
	echo "连接服务器成功";

	// 模式为安装
	if($_GET['step'] == 2)
	{
		// 连接或创建数据库
		CreateDatabase();

		// 创建表
		CreateTables();

	}
	
	// 模式为卸载
	else if($_GET['step'] == 3)
	{
		//delete DB
		$sql = "DROP DATABASE IF EXISTS ".$g_dbDatabase;
		if(mysqli_query($g_dbConnect,$sql))
		{
			echo "<br>Database ".$g_dbDatabase." delete successfully";
		}
		else
		{
			echo "<br>Error delete database: ".mysqli_error($g_dbConnect);
		}
	}
	

?>
