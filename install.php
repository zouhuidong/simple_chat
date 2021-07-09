<?php

	include_once("database.php");
	
?>

<html>
<title>��װ��ж�����ݿ�</title>
<h1 align="center"><font color="blue">��װ��ж�����ݿ�</font></h1>
<hr>
<table>
	<tr>
		<td><a href="?step=2" target="_self">��װ���ݿ�</a></td>
	</tr>
	<tr>
		<td><a href="?step=3" target="_self">ɾ�����ݿ�</a></td>
	</tr>
	
</table>
<hr>
Copyright (C) <?php echo "2018-".date("Y"); ?> huidong
</html>

<?php


	/*
	 *	���ݿ����
	 */
	 
	// ������
	function CreateTables()
	{
		// �õ�ȫ�ֱ���
		$g_dbConnect = $GLOBALS['g_dbConnect'];
		
		echo "<br/>��ʼ������<br/>";
		
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
	
	// �������ݿ⣬���Ѵ����򴴽�
	function CreateDatabase()
	{
		// �õ�ȫ�ֱ���
		$g_dbConnect = $GLOBALS['g_dbConnect'];
		$g_dbDatabase = $GLOBALS['g_dbDatabase'];
		$g_dbCharset = $GLOBALS['g_dbCharset'];
		
		//�������ݿ�
		if (!mysqli_select_db($g_dbConnect,$g_dbDatabase))
		{
			//�������ݿ�ʧ��
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
				echo "<br/>�������ݿ�ɹ���<br/>";
			}
		}
		else
		{
			echo "<br/>�������ݿ�ɹ���<br/>";
		}
	}

	
	/*
	 *	�û�����
	 */
	
	// �������
	if (!$g_dbConnect) {
		die("Connection failed: " . mysqli_connect_error());
	}
	echo "���ӷ������ɹ�";

	// ģʽΪ��װ
	if($_GET['step'] == 2)
	{
		// ���ӻ򴴽����ݿ�
		CreateDatabase();

		// ������
		CreateTables();

	}
	
	// ģʽΪж��
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
