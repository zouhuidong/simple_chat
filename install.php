<?php

	include_once("database.php");
	
?>

<html>
<title>安装和卸载数据库</title>
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
Copyright (C) <?php echo "2018-".date("Y"); ?> huidong
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
			`time` datetime NOT NULL default '0000-00-00 00:00:00',
			`name` text NOT NULL default '',
			`text` text NOT NULL default '',
			`ip` text NOT NULL default '',
			`location` text NOT NULL default '',
			PRIMARY KEY (`id`)
		) TYPE=MyISAM;";
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
		$g_dbCharset = $GLOBALS['g_dbCharset'];
		
		//连接数据库
		if (!mysqli_select_db($g_dbConnect,$g_dbDatabase))
		{
			//连接数据库失败
			echo '<br>could not select database '.$g_dbDatabase;
			echo '<br>create database '.$g_dbDatabase;
			$sql = 'CREATE DATABASE IF NOT EXISTS '.$g_dbDatabase." DEFAULT CHARACTER SET ".$g_dbCharset." COLLATE gbk_chinese_ci";
			if (mysqli_query($g_dbConnect,$sql))
			{
				echo "<br>Database ".$g_dbDatabase." created successfully";
			}
			else
			{
				echo '<br>Error creating database: ' . mysqli_error($g_dbConnect);
			}

			if(mysqli_select_db($g_dbConnect,$g_dbDatabase))
			{
				//set charset
				$sql = "SET NAMES ".$g_dbCharset;
				if(mysqli_query($g_dbConnect,$sql))
				{
					echo "<br>Set charset to ".$g_dbCharset ."successfully";
				}
				else
				{
					echo "<br>Error set charset: ".mysqli_error($g_dbConnect);
				}
			}
			else
			{
				echo "<br/>连接数据库成功。<br/>";
			}
		}
		else
		{
			echo "<br/>连接数据库成功。<br/>";
		}
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
