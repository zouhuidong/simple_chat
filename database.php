<?php
	/*
	 *	���ݿ�����
	 */

	$g_dbSevername;			// ����������
	$g_dbUsername;			// �������û���
	$g_dbPassword;			// ����������
	$g_dbDatabase;			// ���ݿ�����
	$g_dbCharset;			// ���ݿ��ַ���
	$g_dbServerTimeZone;	// ���ݿ�ʱ��
	$g_dbConnect;			// ���ӵ����ݿ�ķ���ֵ
	$g_fpUpdateHistory;		// ������־���ļ�·��
	
	$g_dbSevername = "localhost";
	$g_dbUsername = "root";	
	$g_dbPassword = "";
	$g_dbDatabase = "simple_chat";
	$g_dbCharset = "GBK";
	$g_dbServerTimeZone = "PRC";

	// ����Ĭ��ʱ��
	date_default_timezone_set($g_dbServerTimeZone);
	
	// �����ͷ�����������
	$g_dbConnect = mysqli_connect($g_dbSevername, $g_dbUsername, $g_dbPassword);

	// �������
	if (!$g_dbConnect) {
		echo "����������ʧ��: ".mysqli_connect_error();
		//return;
	}
	else
	{
		// �������ݿ�
		if (!mysqli_select_db($g_dbConnect,$g_dbDatabase))
		{
			echo '<br/>����ѡ�����ݿ⣺ '.$g_dbDatabase.'<br>';
			//return;
		}

		// �����ַ���
		$sql = "SET NAMES ".$g_dbCharset;
		if(!mysqli_query($g_dbConnect,$sql))
		{
			echo "<br/>�������ݿ�ʱ�����ַ���ʧ��: ".mysqli_error($g_dbConnect).'<br>';
			//return;
		}
	}
?>