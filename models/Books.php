<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "books".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $preview
 * @property string $date
 * @property integer $author_id
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $file;
	 
	 
    public static function tableName()
    {
        return 'books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date', 'author_id'], 'required'],
            [['date_create', 'date_update', 'date'], 'safe'],
            [['author_id'], 'integer'],
            [['name', 'preview'], 'string', 'max' => 255],
			[['file'], 'file', 'extensions' => 'jpg, gif, png, bmp'],	
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'date_create' => 'Дата добавления',
            'date_update' => 'Дата обновления',
            'preview' => 'Превью',
            'date' => 'Дата выхода книги',
            'author_id' => 'Автор',
        ];
    }
	
	function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'file');// если файл не пришел сохраняем старое значение
		if ($file) {
			$file_parts = pathinfo($file->name);
			$filename = uniqid().'.'.$file_parts['extension'];
			
			$file->saveAs('upload/'.$filename);
			$this->preview = $filename;
			
			$preview = Image::getImagine()->open('upload/'.$filename);
			$size = $preview->getSize();
			$ratio = $size->getHeight() / $size->getWidth();
			$height = 200;
			$width = round($height/$ratio);
			
			$box = new Box($width, $height);
			$preview->resize($box)->save('upload/prev_'.$filename);
			
		}
	
		return parent::beforeSave($insert);
	}
	
}
