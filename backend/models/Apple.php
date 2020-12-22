<?php
/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 22.12.2020
 * Time: 12:02
 */

namespace backend\models;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $idapple
 * @property string $color
 * @property string $date_appearance
 * @property string|null $date_fall
 * @property string $status
 * @property float $size
 * @property int $quantity
 */

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    public function init(){
        $this->on(ActiveRecord::EVENT_AFTER_FIND,function ($event){
            if($this->date_fall!=null){
                $now_datetime = \DateTime::createFromFormat('Y-m-d G:i:s', date('Y-m-d G:i:s'));
                $date_fall = \DateTime::createFromFormat('Y-m-d G:i:s', $this->date_fall);
                $diff = $now_datetime->diff($date_fall);
                if ($diff->h>=5){
                    $this->status="2";
                    if($this->save()) $event->handled = true;
                }else $event->handled = false;
            }
        });
        parent::init();
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_appearance'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => date('Y-m-d G:i:s'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'apple';
    }

    public static function find()
    {
        return new AppleQuery(get_called_class());
    }

    public function rules()
    {
        return [
            [['color', 'status', 'size', 'quantity'], 'required'],
            [['color'], 'string', 'max' => 10],
            [['date_appearance', 'date_fall'], 'safe'],
            ['status', 'default', 'value' => "0"],
            ['status', 'in', 'range' => ["0", "1", "2"]],
            [['size'], 'number'],
            [['quantity'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idapple' => Yii::t('app', 'ID Apple'),
            'color' => Yii::t('app', 'Color'),
            'date_appearance' => Yii::t('app', 'Date Appearance'),
            'date_fall' => Yii::t('app', 'Date Fall'),
            'status' => Yii::t('app', 'Status'),
            'size' => Yii::t('app', 'Size'),
            'quantity' => Yii::t('app', 'Quantity'),
        ];
    }

    public function eat($percent)
    {
        if ($this->status != "0" || $this->status != "2") {
            if ($percent <= $this->size) {
                $this->size = $this->size - $percent;
                if ($this->save()) return $this->size;
            } else {
                if ($this->delete()) return "съедено";
            }
        } else {
            $echo = "Съесть нельзя";
            if ($this->status == "0") $echo .= "-яблоко на дереве";
            else $echo .= "-яблоко испортилось";
            return $echo;
        }
    }

    public function fallToGroud()
    {
        if ($this->status == '0') {
            $this->date_fall = date('Y-m-d G:i:s');
            $this->status = '1';
            return $this->save();
        } else return true;
    }
}