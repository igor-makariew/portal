<?php

use yii\helpers\Html;

//$this->title = 'Заказ в магазине № ' . $order->id;
//$amounts = null;
//if (!is_null($order->other_amounts)) {
//    $amounts = json_decode($order->other_amounts, true);
//}

?>

<table bgcolor="#f8f8fc" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td align="center" width="100%" bgcolor="#f8f8fc" style="font-family: Tahoma, Verdana, Helvetica, sans-serif; -webkit-text-size-adjust: none; -ms-text-size-adjust: none;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td align="center"><table border="0" cellpadding="0" cellspacing="0" width="600" class="devicewidth" bgcolor="#fff" style="background: #fff;">
                            <tbody>
                            <tr>
                                <td width="100%" valign="middle" colspan="3">
                                    <table align="left" valign="middle" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#025091" style="background: #025091;">
                                        <tbody>
                                        <tr>
                                            <td height="3" width="100%" valign="top"></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" valign="top" align="center">
									<span style="color: #fff; font-size: 18px; font-family: Arial; font-weight: 700; line-height: 22px; display: block;">
										Заказ принят!
									</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="6" width="100%" valign="top"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="14" valign="top"></td>
                                <td width="572" valign="top" align="left">
                                    <table align="left" valign="middle" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <td height="26" width="100%" valign="top" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td width="360" valign="top" align="left">
                                                <table align="left" valign="middle" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td height="7" width="100%" valign="top"></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="16" width="100%" valign="top"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%" valign="top" align="left">
												<span style="color: #004f93; font-size: 13px; font-family: Arial; font-weight: 700; line-height: 16px; display: block;">
													Дата оформления заказа: <?= date("d.m.y") ?>
												</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="26" width="100%" valign="top"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%" valign="top" align="left">
                                                            <table align="left" valign="middle" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td width="48%" valign="top" align="left">
															<span style="color: #004f93; font-size: 13px; font-family: Arial; font-weight: 700; line-height: 16px; display: block;">
																Получатель:
															</span>
                                                                    </td>
                                                                    <td width="4%" valign="top" align="left">

                                                                    </td>
                                                                    <td width="48%" valign="top" align="left">
															<span style="color: #000; font-size: 13px; font-family: Arial; font-weight: 400; line-height: 16px; display: block;">
                                                            <?= $username ?>
															</span>
                                                                    </td>
                                                                    <td width="48%" valign="top" align="left">
															<span style="color: #000; font-size: 13px; font-family: Arial; font-weight: 400; line-height: 16px; display: block;">
															</span>
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td height="6" width="100%" valign="top" colspan="3"></td>
                                                                </tr>
                                                                <tr>

                                                                </tr>

                                                                <tr>
                                                                    <td height="6" width="100%" valign="top" colspan="3"></td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="16" width="100%" valign="top" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" valign="top" align="left" colspan="3">
									<span style="color: #004f93; font-size: 13px; font-family: Arial; font-weight: 700; line-height: 16px; display: block;">
										Состав заказа:
									</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="8" width="100%" valign="top" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" valign="top" align="left" colspan="3">
                                                <table align="left" valign="middle" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-bottom: 1px solid #37a7df;">
                                                    <tbody>
                                                    <tr>
                                                        <td width="30" valign="top" align="center" style="padding: 1px 5px 2px; border: 1px solid #37a7df;">
													<span style="color: #004f93; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
														№
													</span>
                                                        </td>
                                                        <td width="804" valign="top" align="left" style="padding: 1px 11px 2px; border: 1px solid #37a7df;">
													<span style="color: #004f93; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
														Название Отеля
													</span>
                                                        </td>
                                                        <td width="170" valign="top" align="center" style="padding: 1px 5px 2px; border: 1px solid #37a7df;">
													<span style="color: #004f93; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
														Цена
													</span>
                                                        </td>
                                                    </tr>
                                                    <?php foreach($countHotels as $index => $hotel): ?>
                                                        <tr>
                                                            <td width="30" valign="top" align="center" style="padding: 7px 5px 10px; border-left: 1px solid #37a7df;">
                                                        <span style="color: #000; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
                                                            <?=$index + 1; ?>
                                                        </span>
                                                            </td>
                                                            <td width="804" valign="top" align="left" style="padding: 7px 11px 10px; border-left: 1px solid #37a7df;">
                                                        <span style="color: #000; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
                                                        <?= $hotel['name'] ?>
                                                        </span>
                                                            </td>
                                                            <td width="170" valign="top" align="center" style="padding: 7px 5px 10px; border-left: 1px solid #37a7df; border-right: 1px solid #37a7df;">
                                                        <span style="color: #000; font-size: 13px; font-family: Tahoma; font-weight: 400; line-height: 16px;">
                                                        <?= $hotel['price'] ?>
                                                        </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="10" width="100%" valign="top" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" valign="top" align="left" colspan="3">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="29" width="100%" valign="top" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td width="100%" valign="top" align="left" colspan="3">
									<span style="color: #000; font-size: 13px; font-family: Arial; font-weight: 400; line-height: 16px; display: block;">
										Вы получили это письмо, потому что оформили заказ. Данное письмо не требует ответа.
									</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="14" valign="top"></td>
                            </tr>
                            <tr>
                                <td height="20" width="100%" valign="top" colspan="3"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="31" width="100%"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    </tbody>
</table>