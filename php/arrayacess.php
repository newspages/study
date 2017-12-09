<?php
/**
    php如何使得你的对象可以像数组一样可以被访问（ArrayAccess 的作用）？
    2017年11月28日
    PHP预定义接口之 ArrayAccess
　　这篇博客给大家说说关于PHP预定义接口中常用到的重量级人物： ArrayAccess。大家也许会问，最基本、最常用的预定义接口有6个呢，为啥非得说这个。
    从日常的使用情况来看：这个出现的频率非常高，特别是在框架中，比如Laravel、Slim等都会用到，并且用得非常经典，让人佩服啊。从技术上说：说实话其他的我用的少啊！
    只是知道简单的用法，对他的理解比较浅显，不敢在这里误导大家，哈哈！今天我要写的内容也不一定都正确，不对之处还请指正。

ArrayAccess

　　先说 ArrayAccess 吧！ArrayAccess 的作用是使得你的对象可以像数组一样可以被访问。应该说 ArrayAccess 在PHP5中才开始有的，PHP5中加入了很多新的特性，当然也使类的重载也加强了，PHP5 中添加了一系列接口，这些接口和实现的 Class 统称为 SPL。

ArrayAccess 这个接口定义了4个必须要实现的方法：

1 {
2    abstract public offsetExists ($offset)  //检查偏移位置是否存在
3    abstract public offsetGet ($offset)     //获取一个偏移位置的值
4    abstract public void offsetSet ($offset ,$value) //设置一个偏移位置的值
5    abstract public void offsetUnset ($offset)       //复位一个偏移位置的值
6 }
所以我们要使用ArrayAccess这个接口，就要实现相应的方法，这几个方法不是随便写的，我们可以看一下 ArrayAccess 的原型：

*/

/**
  * Interface to provide accessing objects as arrays.
  * @link http://php.net/manual/en/class.arrayaccess.php
  */
 interface ArrayAccess {
     /**
      * (PHP 5 &gt;= 5.0.0)<br/>
      * Whether a offset exists
      * @link http://php.net/manual/en/arrayaccess.offsetexists.php
      * @param mixed $offset <p>
      * An offset to check for.
      * </p>
      * @return boolean true on success or false on failure.
      * </p>
      * <p>
      * The return value will be casted to boolean if non-boolean was returned.
      */
     public function offsetExists($offset);
    /**
      * (PHP 5 &gt;= 5.0.0)<br/>
      * Offset to retrieve
      * @link http://php.net/manual/en/arrayaccess.offsetget.php
      * @param mixed $offset <p>
      * The offset to retrieve.
      * </p>
      * @return mixed Can return all value types.
      */
     public function offsetGet($offset);

     /**
      * (PHP 5 &gt;= 5.0.0)<br/>
      * Offset to set
      * @link http://php.net/manual/en/arrayaccess.offsetset.php
      * @param mixed $offset <p>
      * The offset to assign the value to.
      * </p>
      * @param mixed $value <p>
      * The value to set.
      * </p>
      * @return void
      */
     public function offsetSet($offset, $value);

     /**
      * (PHP 5 &gt;= 5.0.0)<br/>
      * Offset to unset
      * @link http://php.net/manual/en/arrayaccess.offsetunset.php
      * @param mixed $offset <p>
      * The offset to unset.
      * </p>
      * @return void
      */
     public function offsetUnset($offset);
}

/**
 * 下面我们可以写一个例子，非常简单：
 */
class Test implements ArrayAccess
{
     private $testData;

     public function offsetExists($key)
     {
         return isset($this->testData[$key]);
     }

     public function offsetSet($key, $value)
     {
         $this->testData[$key] = $value;
     }

     public function offsetGet($key)
     {
         return $this->testData[$key];
     }

     public function offsetUnset($key)
     {
         unset($this->testData[$key]);
     }
}

   $obj = new Test();

   //自动调用offsetSet方法
   $obj['data'] = 'data';

   //自动调用offsetExists
   if(isset($obj['data'])){
     echo 'has setting!';
   }
   //自动调用offsetGet
   var_dump($obj['data']);
   echo $obj['data'];
   echo $obj->data;

   //自动调用offsetUnset
   unset($obj['data']);
   var_dump($test['data']);

   //输出：
   //has setting!
   //data
   //null

/**
 * 例子二:
 */


/**
 * ArrayAndObjectAccess
 * 该类允许以数组或对象的方式进行访问
 *
 * @author 疯狂老司机
 */
class ArrayAndObjectAccess implements ArrayAccess {

    /**
     * 定义一个数组用于保存数据
     *
     * @access private
     * @var array
     */
    private $data = [];

    /**
     * 以对象方式访问数组中的数据
     *
     * @access public
     * @param string 数组元素键名
     */
    public function __get($key) {
        return $this->data[$key];
    }

    /**
     * 以对象方式添加一个数组元素
     *
     * @access public
     * @param string 数组元素键名
     * @param mixed  数组元素值
     * @return mixed
     */
    public function __set($key,$value) {
        $this->data[$key] = $value;
    }

    /**
     * 以对象方式判断数组元素是否设置
     *
     * @access public
     * @param 数组元素键名
     * @return boolean
     */
    public function __isset($key) {
        return isset($this->data[$key]);
    }

    /**
     * 以对象方式删除一个数组元素
     *
     * @access public
     * @param 数组元素键名
     */
    public function __unset($key) {
        unset($this->data[$key]);
    }

    /**
     * 以数组方式向data数组添加一个元素
     *
     * @access public
     * @abstracting ArrayAccess
     * @param string 偏移位置
     * @param mixed  元素值
     */
    public function offsetSet($offset,$value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * 以数组方式获取data数组指定位置元素
     *
     * @access public
     * @abstracting ArrayAccess
     * @param 偏移位置
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    /**
     * 以数组方式判断偏移位置元素是否设置
     *
     * @access public
     * @abstracting ArrayAccess
     * @param 偏移位置
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    /**
     * 以数组方式删除data数组指定位置元素
     *
     * @access public
     * @abstracting ArrayAccess
     * @param 偏移位置
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

}

$animal = new ArrayAndObjectAccess();

$animal->dog = 'dog'; // 调用ArrayAndObjectAccess::__set
$animal['pig'] = 'pig'; // 调用ArrayAndObjectAccess::offsetSet
var_dump(isset($animal->dog)); // 调用ArrayAndObjectAccess::__isset
var_dump(isset($animal['pig'])); // 调用ArrayAndObjectAccess::offsetExists
var_dump($animal->pig); // 调用ArrayAndObjectAccess::__get
var_dump($animal['dog']); // 调用ArrayAndObjectAccess::offsetGet
unset($animal['dog']); // 调用ArrayAndObjectAccess::offsetUnset
unset($animal->pig); // 调用ArrayAndObjectAccess::__unset
var_dump($animal['pig']); // 调用ArrayAndObjectAccess::offsetGet
var_dump($animal->dog); // 调用ArrayAndObjectAccess::__get

?>
<?php
/**
以上输出：
boolean true
boolean true
string 'pig' (length=3)
string 'dog' (length=3)
null
null
*/