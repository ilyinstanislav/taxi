<?php
namespace common\validators;

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

use yii\validators\Validator;

class XssFilter extends Validator
{
	/**
	 * удаление HTML тегов из аттрибута
	 * @var bool
	 */
	public $purge_html = true;
	
	/**
	 * кодирование HTML тегов
	 * @var bool
	 */
	public $encode_html = true;

	/**
	 * удаление JS из значения аттрибута
	 * @var bool
	 */
	public $purge_js = true;
	
	/**
	 * обрезка пробелов
	 * @var bool
	 */
	public $trim = true;
	
	/**
	 * запуск обработки значения
	 * @param \yii\base\Model $model
	 * @param string $attribute
	 */
    public function validateAttribute($model, $attribute)
    {
        $value = ArrayHelper::getValue( $model, $attribute, null );
        $value = $this->purge($value);

		$model->setAttributes([
			$attribute => $value
		], false);
    }
	
	/**
	 * собственно обработка
	 * @param $value
	 * @return array|string
	 */
	public function purge( $value ){
		if( is_array($value) ){
			foreach($value as $key=>$item){
				$value[$key] = $this->purge($item);
			}
			return $value;
		}
		
		if( $this->trim ){
			$value = trim($value);
		}
		
		if( $this->purge_js ){
			$value = HtmlPurifier::process($value);
		}
		
		if( $this->purge_html){
			$value = strip_tags( $value );
		}
		else if( $this->encode_html ){
			$value = Html::encode($value, false);
		}
		
		return $value;
	}
}
