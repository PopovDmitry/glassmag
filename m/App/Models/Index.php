<?php
/**
 * Модель вывода главной страницы
 */
class App_Models_Index
{
    use App_ClearData;

    private $articul = "";
    //private $oArticul = "";
    private $height = "";
    private $width = "";
    private $materialFrame = "";
    private $quantity = "1";
    private $marking = "";
    private $isCustom;

    private $errors = array();

    public function __construct ()
    {
        if(isset($_REQUEST['btnAdd']))
        {
            $this->articul = $this->clearString($_REQUEST['articul']);
            $this->isCustom = "false"; //$this->isCustom = ($this->articul == "11") ? true : false;
            //$this->oArticul = strip_tags ($_REQUEST['oarticul']);
            $this->height = $this->clearString($_REQUEST['height']);
            $this->width = $this->clearString($_REQUEST['width']);
            $this->materialFrame = $this->clearString($_REQUEST['mframe']);
            $this->quantity = $this->clearString($_REQUEST['quantity']);
            $this->marking = $this->clearString($_REQUEST['marking']);
        }
        $this->errors['articul'] = "";
        //$this->errors['oarticul'] = "";
        $this->errors['height'] = "";
        $this->errors['width'] = "";
        $this->errors['mframe'] = "";
        $this->errors['quantity'] = "";
        $this->errors['marking'] = "";
    }

    public function checkProduct()
    {
        $result = true;

        $tmp[] = "4М1-16-4М1";
        $tmp[] = "4М1-16-4И";
        $tmp[] = "4М1-10-4М1-10-4М1";
        $tmp[] = "4М1-10-4М1-10-4И";
        $tmp[] = "4М1-12-4М1-12-4И";
        $tmp[] = "4М1-12-4М1-12-4М1";
        $tmp[] = "4М1-14-4М1-14-4М1";
        $tmp[] = "4М1-14-4М1-14-4И";
        //$tmp[] = "11";

        if(array_search($this->articul, $tmp) == false)
        {
            $this->errors['articul'] = " is-invalid";

        }
        /* ToDo Проверка для regexp $this->oarticul
         * if($this->oarticul)
         * {
         * $this->errors['oarticul'] = " is-invalid";
         * $result = false;
        }*/
        if(!ctype_digit($this->height))
        {
            $this->errors['height'] = " is-invalid";
            $result = false;
        }
        if(!ctype_digit($this->width))
        {
            $this->errors['width'] = " is-invalid";
            $result = false;
        }
        if($this->materialFrame != "Алюминий" && $this->materialFrame != "CHROMATECH ultra")
        {
            $this->errors['mframe'] = " is-invalid";
            $result = false;
        }
        if(!ctype_digit($this->quantity))
        {
            $this->errors['quantity'] = " is-invalid";
            $result = false;
        }
        if(strlen($this->marking) < 1)
        {
            $this->errors['marking'] = " is-invalid";
            $result = false;
        }

        return $result;
    }

    public function getProduct()
    {
        $values['articul1'] = ($this->articul == "4М1-16-4М1") ? "selected" : "";
        $values['articul2'] = ($this->articul == "4М1-16-4И") ? "selected" : "";
        $values['articul3'] = ($this->articul == "4М1-10-4М1-10-4М1") ? "selected" : "";
        $values['articul4'] = ($this->articul == "4М1-10-4М1-10-4И") ? "selected" : "";
        $values['articul5'] = ($this->articul == "4М1-12-4М1-12-4И") ? "selected" : "";
        $values['articul6'] = ($this->articul == "4М1-12-4М1-12-4М1") ? "selected" : "";
        $values['articul7'] = ($this->articul == "4М1-14-4М1-14-4М1") ? "selected" : "";
        $values['articul8'] = ($this->articul == "4М1-14-4М1-14-4И") ? "selected" : "";
        //$values['articul9'] = ($this->articul == "11") ? "selected" : "";
        //$values['oarticul'] = $this->oarticul;
        $values['width'] = $this->width;
        $values['height'] = $this->height;
        $values['mframe1'] = ($this->materialFrame == "Алюминий") ? "selected" : "";
        $values['mframe2'] = ($this->materialFrame == "CHROMATECH ultra") ? "selected" : "";
        $values['quantity'] = $this->quantity;
        $values['marking'] = $this->marking;

        return $values;
    }


    public function getErrors()
    {
        return $this->errors;
    }


    public function addProduct()
    {
        $product = "0" . ";"; //$product = ($this->isCustom) ? 1 : 0;
        //$product .= ";";
        $product .= $this->articul . ";";//$product .= ($this->isCustom) ? $this->oArticul : $this->articul;

        $product .= $this->height . ";";
        $product .= $this->width . ";";
        $product .= $this->materialFrame . ";";
        $product .= $this->quantity . ";";
        $product .= $this->marking . ";";
        App_Cart::addToCart($product);
    }

} 