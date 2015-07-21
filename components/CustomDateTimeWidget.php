<?php
namespace app\components;

use yii\base\Widget;

class CustomDateTimeWidget extends Widget
{
    public $datetime;

    public function init()
    {
        parent::init();
        if ($this->datetime === null) {
            $this->datetime = date("Y-m-d H:i:s");
        }
    }

    public function run()
    {
		$months = array(
			'01' => 'Января',
			'02' => 'Февраля',
			'03' => 'Марта',
			'04' => 'Апреля',
			'05' => 'Мая',
			'06' => 'Июня',
			'07' => 'Июля',
			'08' => 'Августа',
			'09' => 'Сентября',
			'10' => 'Октября',
			'11' => 'Ноября',
			'12' => 'Декабря',
		);
		$day = 24 * 60 * 60;
		if (date("dmY", strtotime($this->datetime)) === date("dmY")) return "Сегодня";
		if (date("dmY", strtotime($this->datetime) + $day) === date("dmY")) return "Вчера";
		if (date("dmY", strtotime($this->datetime) + 2 * $day) === date("dmY")) return "Позавчера";
		else return date('d ', strtotime($this->datetime)).$months[date('m', strtotime($this->datetime))].date(' Y', strtotime($this->datetime));
    }
}
?>
