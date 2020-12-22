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
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{

    public static function tableName()
    {
        return 'apple';
    }

    public static function find()
    {
        return new AppleQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => 'date_appearance'],
                'value' => date('Y-m-d G:i:s', time() - 3600 * 24 * (int)rand(55, 60)),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => 'color'],
                'value' => function ($event) {
                    $colors = ['green', 'red', 'yellow'];
                    return $colors[(int)rand(0, 2)];
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => 'size'],
                'value' => 100,
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_UPDATE => 'date_fall'],
                'value' => function () {
                    if ($this->status == '1' && $this->date_fall==null) {
                        return date('Y-m-d G:i:s');
                    } else return $this->date_fall;
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'status',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'status',
                ],
                'value' => function ($event) {
                    if ($this->date_fall != null) {
                        $now_datetime = \DateTime::createFromFormat('Y-m-d G:i:s', date('Y-m-d G:i:s'));
                        $date_fall = \DateTime::createFromFormat('Y-m-d G:i:s', $this->date_fall);
                        $diff = $now_datetime->diff($date_fall);
                    }
                    if ($this->status == '1' && $diff->h >= 5) {
                        return "2";
                    } else return $this->status;
                },
            ],
        ];
    }

    public function rules()
    {
        return [
            [['color'], 'string', 'max' => 10],
            [['date_appearance', 'date_fall'], 'safe'],
            ['status', 'default', 'value' => "0"],
            ['status', 'in', 'range' => ["0", "1", "2"]],
            [['size'], 'number'],
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
        ];
    }

    public function eat($percent)
    {
        if ($this->status != "0" && $this->status != "2") {
            if ($percent <= $this->size) {
                $this->size = $this->size - $percent;
                if($this->size==0) $this->delete();
                else return $this->save();
            } else {
                $this->delete();
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