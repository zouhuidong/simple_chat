# simple_chat
A simple chat web made by php.

一个简易的聊天网站，由php制作。xampp下运行正常。

使用方法：

1.在database.php中设置数据库信息

2.运行install.php，安装数据库。

3.运行database.php即可。


index.php中设置$cmd变量可以设置密钥。

密钥作用：可以在输入框中输入html语句

比如，默认密钥是cmd，在要发送的文本内容中，以"cmd"开头，后面写的内容中可以添加html语句，不会被删除，如果没有添加"cmd"，则发送的html语句会被删除

临时昵称中无需添加密钥，添加密钥后对文本和昵称都起作用。

