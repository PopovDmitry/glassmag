<?php
/**
 * Клас для отсылки имейлов
 */

class App_Email
{
    private $name;
    private $nameTo;
    private $mailTo;
    private $replyTo;
    private $nameFrom;
    private $mailFrom;
    private $nameOrder;
    private $mailOrder;
    private $message = "";
    private $file;


    public function __construct ($nameOrder, $mailOrder)
    {
        $mail = parse_ini_file('config/mail.ini');
        $this->mailTo = $mail['mailTo'];
        $this->nameTo = $mail['nameTo'];
        $this->replyTo = $mail['replyTo'];
        $this->mailFrom = $mail['mailFrom'];
        $this->nameFrom = $mail['nameFrom'];
        $this->name = date('ymd') . rand(1, 999);
        $this->nameOrder = $nameOrder;
        $this->mailOrder = $mailOrder;
    }

    /*
     * функция отправки плайн текст сообщения с вложенным файлом
     */
    /*public function mailAttachmentText($fileName, $fileContent, $mailTo, $fromMail, $fromName, $replyTo, $subject, $emailText) {

        $content = chunk_split(base64_encode($fileContent));
        $uid = md5(uniqid(time()));

        $header = "To: ".$mailTo."\r\n";
        $header .= "From: ".$fromName." <".$fromMail.">\r\n";
        $header .= "Reply-To: ".$replyTo."\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n\r\n";

        $message = "--".$uid."\r\n";
        $message .= "Content-type:text/plain; charset=utf-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= $emailText."\r\n\r\n";
        $message .= "--".$uid."\r\n";
        $message .= "Content-Type: application/octet-stream; name=\"".$fileName."\"\r\n"; // use different content types here
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-Disposition: attachment; filename=\"".$fileName."\"\r\n\r\n";
        $message .= $content."\r\n\r\n";
        $message .= "--".$uid."--";

        $envelope = "-f" . $fromMail;

        return mail($mailTo, $subject, $message, $header, $envelope);
    }*/

    public function setFileContent($orderDate, $orderDelivery)
    {
        $fileName = strval($this->name) . "_f" . strval((is_null($this->file) ? 1 : count($this->file) + 1)) . ".imp";
        $this->file[$fileName] = App_Converter::generateFileContent($orderDate, $orderDelivery);

        // для тестов, записываю файл
        $fp = fopen($fileName, "w");
        fwrite($fp, $this->file[$fileName]);
        fclose($fp);
    }


    public function setMessageHtml($orderDelivery, $orderDate, $orderPhone)
    {
        $delivery = ($orderDelivery == "0") ? "Компания" : "Самовывоз";
        $this->message = '<!DOCTYPE HTML PUBLIC "-/W3C/DTD HTML 4.01 Transitional/EN" "http://www.w3.org/TR/html4/loose.dtd">' . "\r\n";
        $this->message .= "<html>\r\n<head>\r\n";
        $this->message .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >' . "\r\n";
        $this->message .= "<title>Заказ №$this->name</title>\r\n";
        $this->message .= "</head>\r\n<body>\r\n";
        $this->message .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f5f5f5; min-width: 340px; font-size: 1px; line-height: normal;">' . "\r\n";
        $this->message .= "<tr>\r\n";
        $this->message .= '<td align="center" valign="top">' . "\r\n";
        $this->message .= '<table cellpadding="0" cellspacing="0" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #ffffff;">' . "\r\n";
        $this->message .= "<tr>\r\n";
        $this->message .= '<th align="center" valign="top"><h1><font face="Arial, sans-serif" color="#000" style="font-size: 16px; line-height: 20px;">Заказ № ' . "$this->name</font></h1></th>\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= "<tr><td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">&nbsp;</font></td></tr>\r\n";
        $this->message .= "<tr><td>\r\n";
        $this->message .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">' . "\r\n";
        $this->message .= "<tr>\r\n";
        $this->message .= '<th  colspan="2" align="left" valign="top"><font face="Arial, sans-serif" color="#000" style="font-size: 11px; line-height: 16px;">Заказчик</font></th>' . "\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= '<tr><td width="5%">' . "</td>\r\n";
        $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$this->nameOrder</font></td>\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= '<tr><td width="5%"></td>' . "\r\n";
        $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">телефон: $orderPhone</font></td>\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= "<tr><td width=\"5%\"></td>\r\n";
        $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">email: $this->mailOrder</font></td>\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= "<tr><td width=\"5%\"></td>\r\n";
        $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">Тип доставки: $delivery</font></td>\r\n";
        $this->message .= "</tr>\r\n";
        $this->message .= "<tr><td width=\"5%\"></td>\r\n";
        $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">Дата исполнения заказа: $orderDate</font></td>\r\n";
        $this->message .= "</tr>\r\n</table></td>\r\n";
        $this->message .= "</tr><tr><td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">&nbsp;</font></td></tr>\r\n";
        $this->message .= "<tr>\r\n<td>\r\n";
         $this->message .= '<table cellpadding="0" cellspacing="0" border="1" width="100%">' . "\r\n";
        $this->message .= "<tr>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Артикул</font></th>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Высота, мм</font></th>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Ширина, мм</font></th>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Рамка</font></th>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Количество, шт</font></th>\r\n";
        $this->message .= "<th><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 13px; line-height: 18px;\">Маркировка</font></th>\r\n";
        $this->message .= "</tr>\r\n";
        foreach ($_SESSION['cart'] as $key => $value)
        {
            $product = explode(";", $value);
            $this->message .= "<tr align=\"center\">\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[1]</font></td>\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[2]</font></td>\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[3]</font></td>\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[4]</font></td>\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[5]</font></td>\r\n";
            $this->message .= "<td><font face=\"Arial, sans-serif\" color=\"#000\" style=\"font-size: 11px; line-height: 16px;\">$product[6]</font></td>\r\n";
            $this->message .= "</tr>\r\n";
        }
        $this->message .= "</table></td></tr>\r\n";
        $this->message .= "</table>\r\n";
        $this->message .= "</td></tr></table>\r\n";
        $this->message .= "</body>\r\n";
        $this->message .= "</html>";
    }

    /**
     * функция отправки хтмл сообщения с вложенными файлами
     * @return bool успешность отправки письма
     */
    public function mailAttachmentHtml()
    {
        $EOL = "\r\n";
        $boundary = "--".md5(uniqid(time()));

        $subject = '=?utf-8?B?' . base64_encode("Заказ №" . $this->name) . '?=';

        $headers = "MIME-Version: 1.0;" . $EOL . "";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"" . $EOL . "";
        $headers .= "From: " . $this->nameFrom ." <".$this->mailFrom . ">\nReply-To: " . $this->replyTo . $EOL . "";//"\n";
        $headers .= 'Date: ' . date('r') . $EOL . "";
        $multipart  = "--" . $boundary . $EOL;
        $multipart .= "Content-Type: text/html; charset=utf-8" . $EOL . "";
        $multipart .= "Content-Transfer-Encoding: base64" . $EOL . "";
        $multipart .= $EOL;
        $multipart .= chunk_split(base64_encode($this->message));

        foreach($this->file as $fileName => $dataFile)
        {
            $multipart .=  "" . $EOL . "--" . $boundary . $EOL . "";
            $multipart .= "Content-Type: application/octet-stream; name=\"" . $fileName . "\"" . $EOL . "";
            $multipart .= "Content-Transfer-Encoding: base64" . $EOL . "";
            $multipart .= "Content-Disposition: attachment; filename=\"" . $fileName . "\"" . $EOL . "";
            $multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла
            $multipart .= chunk_split(base64_encode($dataFile));
        }
        $multipart .= "" . $EOL . "--" . $boundary . "--" . $EOL . "";
        return mail($this->mailTo, $subject, $multipart, $headers);
    }
}