<?php
require_once(dirname(__FILE__).'/../setting.php');
require_once(PATH_PHP.'functions.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

//データベースとの通信をする
class DataBase
{
  //プライベートプロパティ
  public $dblink = null;

  //パブリックプロパティ
  public $previousQuery = "";
  public $result = null;
  public $error = null;

  //コンストラクタ
  public function __construct()
  {
    $this->connect();
  }

  //デストラクタ
  public function __destruct()
  {
    $this->disconnect();
  }

  //メソッド
  public function Show()
  {
    //echo 'database : '.$this->dblink.'<br>';
    if($this->dblink != null)
    {
      echo 'connecting is successful!<br>';
    }
    else
    {
      echo 'connecting is false...<br>';
    }
  }

  /*
   * SQLを実行する
   * 実行結果は$this->resultに格納される．
   */
  public function ExecuteSQL($query, $binds = null)
  {
    //sqlの実行
    if($stmt = $this->dblink->prepare($query))
    {
      for($i = 0; $i < count($binds); $i++)
      {
        $stmt->bind_param("is", );
      }
    }
    $result = $this->dblink->query($query);
    if(!$result)
    {
      error('SELECTクエリに失敗しました:'.$this->dblink->error);
      $this->error = 'SELECTクエリに失敗しました:'.$this->dblink->error;
    }

    //取得したデータの格納
    if(!is_bool($result))
    {
      $this->result = $result->fetch_all($resulttype=MYSQLI_ASSOC);
      $result->close();
    }

    return $this->result;
  }

  /*
   * データベースに接続する
   */
  private function connect()
  {
    $this->dblink = connectingMySQL();
  }

  /*
   * データベースから切断する
   */
  private function disconnect()
  {
    $isSuccess = closingMySQL($this->dblink);
    if(!$isSuccess)
    {
      $this->error = "切断に失敗しました.";
    }
    $this->dblink = null;
  }
}

//ユーザ情報を格納するクラス
class UserInfo extends DataBase
{
  //プロパティ
  public $id = -1;          //ユーザID
  public $nicname = '';     //ユーザ名（ニックネーム）
  public $firstname = '';   //名
  public $lastname = '';    //姓
  public $age = -1;         //年齢
  public $departmentId = -1;//学科
  public $grade = -1;       //学年

  private $username = "";   //ユーザ名
  private $password = "";   //パスワード
  private $cache = "";      //キャッシュ

  //コンストラクタ
  public function __construct($link = null)
  {
    //接続していないなら接続する．
    if($link == null)
    {
      parent::__construct();
    }
    else
    {
      $this->dblink = $link;
    }
    $this->id = 0;
    $this->nicname = 'noname';
  }

  //デストラクタ
  public function __destruct()
  {
    parent::__destruct();
  }

  //メソッド
  public function Show()
  {
    parent::Show();
    echo $this->id.'<br>';
    echo $this->nicname.'<br>';
    echo $this->firstname.'<br>';
    echo $this->lastname.'<br>';
    echo $this->age.'<br>';
    echo $this->departmentId.'<br>';
    echo $this->grade.'<br>';
  }

  //ログイン
  public function Login($username, $password)
  {
    $this->username = $username;
    $this->password = $password;

  }

  //ログアウト
  public function Logout()
  {

  }

  //ユーザー登録
  public function Signup(
    $username,    //ユーザ名
    $password,    //パスワード
    $nicname,     //ユーザ名（ニックネーム）
    $firstname = '',   //名
    $lastname = '',    //姓
    $age = -1,         //年齢
    $departmentId = -1,//学科
    $grade = -1        //学年
    )
  {
    //$this->ExecuteSQL('insert into user_info(username, password, nicname, firstname, lastname, age, departmentid, grade) values(\''.$username.'\', \''.$password.'\', \''.$nicname.'\', \''.$firstname.'\', \''.$lastname.'\', '.$age.', '.$departmentId.', '.$grade.')');
    $query = 'insert into user_info(username, password, nicname, firstname, lastname, age, departmentid, grade) values(?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $this->ExecuteSQL($query, array($username, $password, $nicname, $firstname, $lastname, $age, $departmentId, $grade));

    if($this->result)
    {
      $this->username = $username;
      $this->password = $password;
      $this->nicname = $nicname;
      $this->firstname = $firstname;
      $this->lastname = $lastname;
      $this->age = $age;
      $this->departmentId = $departmentId;
      $this->grade = $grade;
    }
    else {
      error("sign up error...");
    }
  }
}

class UserPost extends DataBase
{
  //プロパティ
  public $id = -1;            //ポストID
  public $title = "";         //ポストタイトル
  public $contents = "";      //ポスト内容
  public $keywords = "";      //キーワード
  public $userid = -1;        //投稿者ユーザID
  public $postdate = null;    //投稿日
  public $update = null;      //更新日

  //コンストラクタ
  public function __construct($link = null)
  {
    //接続していないなら接続する．
    if($link == null)
    {
      parent::__construct();
    }
    else
    {
      $this->dblink = $link;
    }

    $this->id = 0;
    $this->title = "no title";
  }

  //デストラクタ
  public function __destruct()
  {
    parent::__destruct();
  }

  //ポストの新規作成
  public function NewPost()
  {
    //DataBaseをみてidを決定する．
  }

  //ポストの読み込み
  public function EditPost($id)
  {
    //idからポストを読み込む
    //なければ新規作成
  }

  //投稿する
  public function Post()
  {
    //すでにポストが存在するなら_updatePostを実行
    //存在しないなら_initPostを実行
  }

  //ポストを新たに投稿する
  private function _initPost()
  {

  }

  //ポスト情報を更新する
  private function _updatePost()
  {

  }
}

?>
