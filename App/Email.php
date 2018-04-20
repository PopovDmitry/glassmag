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

    public function setFileContent($orderPhone, $orderDate)
    {
        /*
         * File format:  - ASCII-text file with fixed record size
         * - Each record terminated by CR LF
         *    - Leave 1 space between each data field
         *    - Every unused field has to be filled with spaces
         *    - Date field format... DD/MM/YY  e.g. 28/06/02
         *    - Numeric 6,2 => e.g. 1234,56
         *    - The record order must be correct, otherwise the system will recognise an
         *      other order or order item
         *
         *    - Z: Field must be validated
         *    - B: Field can be filled with blanks if there is no data available;
         *      it will only be read into an order if it contains (non blank) data
         *      (like type BL if ‘fld_uebern’ = 1)
         *    - BL: Field can be filled with blanks if there is no data available
         *
         *    fld_uebern = 0: field will always be read into an order – even if its blank
         *    fld_uebern = 1: field will only be read into an order if its not blank
         */

        /*
         * Record Layout “Release”
         *
         * Field Name  Contents, Description                      Format   Length
         * rec_mode    Record mode 99… Release                    numeric  2  Z
         * vers_nr     Version                                    numeric  2  Z
         * Take_over   Take over blank fields                     numeric  1  Z
         * Encoding    File character encoding, blank for default alpha    30  B
         * */
        $content = mb_convert_encoding("99 14 0                               \r\n", 'cp1251', 'auto');

        /*
         * Record layout “HEADER“
         */
        // Field name                   Contents, Description                Format   Length
        // rec_mode                     Record mode 01… Header               numeric  2    Z
        // rec_type                     Record type 0.. new                  numeric  2    Z
        //                              1.. modify 2.. delete
        //                              3.. new with order number
        //                              4.. confirm order
        $headerString = " 1  0";
        // order_no                     Order Number                         numeric  9    Z
        $headerString .= " " . self::setStringLength(9, $this->name);
        // oder_type                    Order Type                           numeric  2    Z
        $headerString .= "  0";
            // hdr.flds.cust_ord_no         Customer Order Number                alpha    60   B
        $headerString .= " " . self::setStringLength(60, "Importer, " . date('d.m.Y,i:G:s'));
        // hdr.flds.cust_ord_date       Customer Order Date                  date     8    B
        $headerString .= " " . date('d.m.y');
        // hdr.flds.deliv_estimated     Estimated Delivery Date              alpha    15   B
        $headerString .= "                ";
        // hdr.flds.deliv_date          Delivery Date                        date     8    B
        $headerString .= " " . date('d.m.y', strtotime($orderDate));
        // hdr.flds.cust_no             Customer number                      numeric  6    Z
        // pseudo_no                    Pro Forma Customer number            numeric  6    Z
        // hdr.deliv.addr_no            Delivery Address ID                  numeric  3    B
        $headerString .= "    999      0   1";
        // hdr.flds.infl_suppl_pct      Inflation supplement                 numeric  5.2  B
        // hdr.flds.price_system_no     Price System                         numeric  4    B
        // hdr.flds.auto_discount       Auto Discount                       numeric  1    B
        // hdr.flds.sales_rep_no        Sales Representative                numeric  5    B
        $headerString .= "                    ";
        // hdr.flds.project_no          Project Number                      alpha    20   B
        // hdr.flds.mounting_instr      Mounting instructions               alpha    20   B
        $headerString .= "                                          ";
        // hdr.flds.venting_tubes       Venting tubes                       numeric  1    B
        // hdr.flds.user_code           User Code                           alpha    20   B
        $headerString .= "                       ";
        // tot.flds.reduction_price     Discount                            numeric  11.2 B
        // tot.flds.transp_costs_hours  Transport Costs Hours               numeric  8.2  B
        // tot.flds.transp_costs_km     Transport Costs km                  numeric  8.2  B
        $headerString .= "                                 ";
        // hdr.flds.site_code           Site Code                           alpha    15   B
        // hdr.flds.currency_code       Currency Code                       alpha    4    B
        $headerString .= "                     ";
        // hdr.flds.currency_rate       Currency Rate                       numeric  9.4  B
        // tot.flds.reduction_vat_code  VAT code discount                   alpha    4    B
        // hdr.deliv.route              Delivery route                      numeric  4    B
        // hdr.deliv.seq                Delivery Sequence                   numeric  3    B
        $headerString .= "                         ";
        // reason                       Reason Codes                        alpha    10   B
        $headerString .= "          0";
        // hdr.deliv.type               Delivery type                       numeric  2    B
        // hdr.flds.wind_pressure       Wind Pressure                       numeric  4    B
        // hdr.flds.express_yn          Express delivery                    numeric  1    B
        // hdr.flds.deliv_stock         Delivery Stock                      alpha    15   B
        $headerString .= "                          ";
        // hdr.deliv.addr.name          Delivery Address – Name             alpha    40   B
        $headerString .= " " . self::setStringLength(40, $this->nameOrder);
        // hdr.deliv.addr.opening       Delivery Address – Opening          alpha    40   B
        $headerString .= "                                         ";
        // hdr.deliv.addr.line1         Delivery Address – Line1            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line2         Delivery Address – Line2            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line3         Delivery Address – Line3            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line4         Delivery Address – Line4            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line5         Delivery Address – Line5            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line6         Delivery Address – Line6            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.line7         Delivery Address – Line7            alpha    60   B
        $headerString .= "                                                             ";
        // hdr.deliv.addr.post_code     Postal Code                         alpha    10   B
        $headerString .= "           ";
        // hdr.deliv.addr.phone_no      Phone number                        alpha    20   B
        $headerString .= " " . self::setStringLength(20, $orderPhone);
        // hdr.deliv.addr.fax_no        Fax number                          alpha    20   B
        $headerString .= "                     ";
        // hdr.deliv.addr.email         Email                               alpha    60   B
        $headerString .= "                                                             ";//" " . self::setStringLength(60, $this->mailOrder);
        // hdr.deliv.addr.country       Country                             alpha    20   B
        $headerString .= "                     ";
        // hdr.flds.sales_rep_no2       Sales Representative 2              numeric  5    B
        // hdr.flds.cust_deliv_date     Customer delivery date              date     8    B
        // hdr.flds.stock_ident         Stock Ident                         alpha    15   B
        $headerString .= "                               ";
        // hdr.flds.sg_price_table_no   Single Glass Price Table Number     numeric  4    B
        // hdr.flds.anc_price_table_no  Ancillaries Price Table Number      numeric  4    B
        // hdr.flds.entry_date          Entry date                          date     8    B
        // hdr.flds.price_calc_date     Price calculation date              date     8    B
        $headerString .= "                            ";
        // hdr.flds.ig_recycle_tax      DGU recycling charge                numeric  9.2  B
        // hdr.flds.energy_wght         Energy surcharge                    numeric  11.4 B
        $headerString .= "                        ";
        // hdr.flds. rtax_val           Road tax 1 (per length and weight)  numeric  11.4 B
        // hdr.flds. rtax_perc          Road tax 2 (% per length)           numeric  11.4 B
        $headerString .= "                          ";
        // hdr.deliv.distance           Distance                            numeric  8.1  B
        // hdr.purch.prod_intext        Purchase type: 0=self production;   numeric  1    B
        //                              1=external; 2=internal
        // hdr.purch.suppl_no           Purchase Supplier no                numeric  10   B
        // hdr.purch.deliv_instr_ext    Purchase delivery: 0=back to        numeric  1    B
        //                              production;1=direct to customer
        $headerString .= "                         \r\n";
        $content .= mb_convert_encoding($headerString, 'cp1251', 'auto');

        /*
         * Record layout “ITEM“
         */
        foreach($_SESSION['cart'] as $key => $order) {
            $product = explode(";", $order);
            // Field name                      Contents, Description              Format  Length
            // rec_mode                        Record Mode 4… Item                numeric  2    Z
            // rec_type                        Record Type                        numeric  2    Z
            // order_no                        Order Number                       numeric  9    Z
            $itemString = " 4  0         0";
            // order_pos                       Order Item Number                  numeric  3    Z
            $itemString .= " " . self::setStringLength(3, strval($key + 1));
            // variant                         Variant                            numeric  2    Z
            $itemString .= "  0";
            // item.flds.quantity              Quantity                           numeric  7.2  Z
            $itemString .= self::setStringLength(9, $product[5] . ".00");
            // item.flds.item_mode             Item Mode                          alpha    1    Z
            //                                 (E... single glass, I... IGU,
            //                                 F... Functional IGU,
            //                                 V... Ancillaries)
            // item.flds.calc_mode             Auto calculation                   num      1     Z
            $itemString .= " F 2";
            // item.flds.width                 Width                              numeric  7.3   Z
            $itemString .= " " . self::setStringLength(8, $product[3] . ".000"); //" " . " 556.000"
            // item.flds.height                Height                             numeric  7.3   Z
            $itemString .= " " . self::setStringLength(8, $product[2] . ".000"); // . " " . " 806.000";
            // item.flds.func_code             Functional IGU Code                numeric  5     Z
            $itemString .= "     1";
            // item.flds.item_text             Item Text                          alpha    50    B
            $itemString .= " " . self::setStringLength(50, $product[1]);
            // item.flds.glass1_code           Glass code 1                       numeric  9     Z
            // item.flds.glass2_code           Glass code 2                       numeric  9     B
            // item.flds.glass3_code           Glass code 3                       numeric  9     B
            // 4М1-10004 4И-21004
            // " " . "    10004" . " " . "    10004" . " " . "         ";
            $itemString .= self::getGlassCode($product[1]);
            // item.flds.mirror_glass1         Mirror Glass 1                     numeric  1     Z
            // item.flds.mirror_glass2         Mirror Glass 2                     numeric  1     Z
            // item.flds.mirror_glass3         Mirror Glass 3                     numeric  1     Z
            // item.flds.struct_orient1        Orientation Glass 1 (0=not valid,  numeric  1     B
            //                                 1=accord. width, 2=accord.
            //                                 height)
            // item.flds.struct_orient2        Orientation Glass 2                numeric  1     B
            // item.flds.struct_orient3        Orientation Glass 3                numeric  1     B
            $itemString .= " 0 0 0      ";
            // item flds.spf1_code             Spacer Frame Code 1                numeric  4     B
            // item.flds.spf2_code             Spacer Frame Code 2                numeric  4     B
            //2-пластик 16-ширина рамки 1-аллюминий
            $itemString .= self::getFrameCode($product[1], $product[4]);
            // item.flds.gas1_code             Gas Code 1                         numeric  4     B
            // item.flds.gas2_code             Gas Code 2                         numeric  4     B
            // item.flds.seal_code             Seal Code                          numeric  4     B
            $itemString .= "          " . " " . "   1";
            // item.flds.pr_m2_gross           Gross price/m (auto_jn = 0)        numeric  15.2  Z
            // item.flds.pr_gross              Gross price / piece (auto_jn = 0)  numeric  15.2  Z
            $itemString .= " " . "            0.00" . " " . "          0.0000";
            // item.flds.item_discount         Item Discount                      numeric  15.2  Z
            // item.flds.inset                 Inset                              numeric  3.1   Z
            $itemString .= " " . "             0.0" . " " . " 0.0";
            // item.flds.glazing               Glazing code                       numeric  4     Z
            // item.flds.vat_code              VAT Code                           alpha    4     B
            $itemString .= " " . "   0" . " " . "    ";
            // item.flds.cost_centre           Cost Center                        alpha    20    B
            // item.flds.auto_cost_centre      auto Cost Center                   numeric  1     B
            // item.flds.auto_vat_code         auto VAT Code                      numeric  1     B
            // item.flds.package_size          Packaging Size                     numeric  4     Z
            $itemString .= "                             0";
            // item.flds.cust_item_no          Customer Item Number               alpha    60    B
            $itemString .= " " . $this->setStringLength(60, $product[6]);
            // item.flds.deliv_tolerance       Delivery tolerance                 alpha    2     B
            // item.flds.drawing_ref           Drawing reference                  alpha    30    B
            $itemString .= "                                  ";
            // item.flds.package_type          Packaging type                     numeric  3     B
            // item.flds.credlim_code          Credit Limit code                  numeric  1     B
            // item.flds.pr_m2_gross_pcu       Gross price per m2                 numeric  15.2  B
            // item.flds.pr_gross_pcu          Gross price per unit               numeric  15.2  B
            // item.flds.pr_curr_code          Price Currency Code                alpha    4     B
            // item.flds.pr_m2_gross_diff_pcu  Gross Price difference/ms          numeric  15.2  B
            // item.flds.pr_gross_diff_pcu     Gross Price difference/piece       numeric  15.2  B
            // item.flds.price_base            Order Item Price Base              numeric  2     B
            // item.flds.uom                   Qty Unit of measurement            numeric  2     B
            // item.flds.number_of_pack        Number of Packages                 numeric  5     B

            $itemString .= "                                                                                           \r\n";

            $content .= mb_convert_encoding($itemString, 'cp1251', 'auto');
        }
        $fileName = strval($this->name) . "_f" . strval((is_null($this->file) ? 1 : count($this->file) + 1)) . ".imp";
        $this->file[$fileName] = $content;
    }

    private static function setStringLength($stringLength, $str)
    {
        while(iconv_strlen($str) < $stringLength)
        {
            $str = " " . $str;
        }
        return $str;
    }


    private static function getFrameCode($articul, $materialFrame)
    {
        $tmp = explode("-", $articul);
        $result = "";
        $result .= " " . self::setStringLength(4, self::setFrameCode($tmp[1], $materialFrame));
        if(count($tmp) == 5)
        {
            $result .= " " . self::setStringLength(4, self::setFrameCode($tmp[3], $materialFrame));
        }
        else
        {
            $result .= "     ";
        }
        return $result;
    }


    private static function setFrameCode($frame, $materialFrame)
    {
        if ($materialFrame == "Алюминий")
        {
            $result = "1" . $frame;
        }
        elseif ($materialFrame == "CHROMATECH ultra")
        {
            $result = "2" . $frame;
        }
        else
        {
            $result = "";
        }
        return $result;
    }


    private static function getGlassCode($articul)
    {
        $tmp = explode("-", $articul);
        $result = "";
        $result .= " " . self::setStringLength(9, self::setGlassCode($tmp[0]));
        $result .= " " . self::setStringLength(9, self::setGlassCode($tmp[2]));
        if (count($tmp) == 5)
        {
            $result .= " " . self::setStringLength(9, self::setGlassCode($tmp[4]));
        }
        else
        {
            $result .= "          ";
        }
        return $result;
    }


    private static function setGlassCode($glass)
    {
        if ($glass == "4М1")
        {
            $result = "10004";
        }
        elseif ($glass == "4И")
        {
            $result = "21004";
        }
        else
        {
            $result = "";
        }
        return $result;
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

        $headers    = "MIME-Version: 1.0;" . $EOL . "";
        $headers   .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"" . $EOL . "";
        $headers   .= "From: ". $this->nameFrom ." <".$this->mailFrom . ">\nReply-To: " . $this->replyTo . "\n";

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