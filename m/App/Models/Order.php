<?php
/**
 * Модель вывода страницы gjregrb
 */
class App_Models_Order
{
    use App_ClearData;

    private $delivery = "1";
    private $orderDate;
    private $orderName = "";
    private $orderPhone = "";
    private $orderEmail = "";

    private $errors;

    public function __construct()
    {
        if (isset($_REQUEST['btnOrder']))
        {
            $this->delivery = $this->clearString($_REQUEST['delivery']);
            $this->orderDate = $this->clearString($_REQUEST['order-date']);
            $this->orderName = $this->clearString($_REQUEST['order-name']);
            $this->orderPhone = $this->clearString($_REQUEST['order-phone']);
            $this->orderEmail = $this->clearString($_REQUEST['order-email']);
        }
        else
        {
            $this->orderDate = date('Y-m-d', strtotime("+1 day"));
            if(isset($_SESSION['order']))
            {
                $this->orderName = $_SESSION['order']['order-name'];
                $this->orderPhone = $_SESSION['order']['order-phone'];
                $this->orderEmail = $_SESSION['order']['order-email'];
            }

        }
        $this->errors['delivery'] = "";
        $this->errors['order-date'] = "";
        $this->errors['order-name'] = "";
        $this->errors['order-phone'] = "";
        $this->errors['order-email'] = "";
    }

    /**
     * @return bool данные прошли проверку
     */
    public function checkOrder()
    {
        $result = true;
        if($this->delivery != "1" && $this->delivery != "2")
        {
            $this->errors['delivery'] = " is-invalid";
            $result = false;
        }
        if(strtotime($this->orderDate) < strtotime(date('Y-m-d')))
        {
            $this->errors['order-date'] = " is-invalid";
            $result = false;
        }
        if(strlen($this->orderName) < 5)
        {
            $this->errors['order-name'] = " is-invalid";
            $result = false;
        }
        if(!preg_match('#^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$#', $this->orderPhone))
        {
            $this->errors['order-phone'] = " is-invalid";
            $result = false;

        }
        if(!filter_var($this->orderEmail, FILTER_VALIDATE_EMAIL))
        {
            $this->errors['order-email'] = " is-invalid";
            $result = false;
        }
        if($result)
        {
            $order ['order-name'] = $this->orderName;
            $order ['order-phone'] = $this->orderPhone;
            $order ['order-email'] = $this->orderEmail;
            $_SESSION['order'] = $order;
            App_Session::saveSession();
        }
        return $result;
    }


    public function getOrder()
    {
        $values['delivery1'] = ($this->delivery == "1") ? "checked" : "";
        $values['delivery2'] = ($this->delivery == "2") ? "checked" : "";
        $values['order-date'] = $this->orderDate;
        $values['order-name'] = $this->orderName;
        $values['order-phone'] = $this->orderPhone;
        $values['order-email'] = $this->orderEmail;

        return $values;
    }


    /**
     * @return array возвращает стили с ошибками для вьюшки
     */
    public function getErrors()
    {
        /*$this->errors['delivery'] = " is-invalid";
        $this->errors['order-date'] = " is-invalid";
        $this->errors['order-name'] = " is-invalid";
        $this->errors['order-phone'] = " is-invalid";
        $this->errors['order-email'] = " is-invalid";*/
        return $this->errors;
    }

    /**
     * @return bool результат отправки email
     */
    public function sendOrder()
    {
        date_default_timezone_set('Asia/Yekaterinburg');
        $email = new App_Email($this->orderName, $this->orderEmail);
        $email->setMessageHtml($this->delivery, $this->orderDate, $this->orderPhone);
        $email->setFileContent($this->orderDate, $this->delivery);
        return $email->mailAttachmentHtml();
    }
} 