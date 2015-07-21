<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Books;

/**
 * BooksSearch represents the model behind the search form about `app\models\Books`.
 */
class BooksSearch extends Books
{
    /**
     * @inheritdoc
     */
	public $date_to; 
	public $date_from; 
	 
	 
    public function rules()
    {
        return [
            [['id', 'author_id'], 'integer'],
            [['date_to', 'date_from'], 'string'],
            [['name', 'date_create', 'date_update', 'preview', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Books::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		//var_dump($this->date_create); die('--');
		if ($this->date) $date = date("Y-m-d", strtotime($this->date));
		else $date = $this->date;
		
        $query->andFilterWhere([
            'id' => $this->id,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            //'date' => $this->date,
            'date' => $date,
            'author_id' => $this->author_id,
        ]);
		
		if($this->date_from) $query->andFilterWhere(['>=', 'date', date("Y-m-d", strtotime(str_replace("/", ".", $this->date_from)))]);
		if($this->date_to) $query->andFilterWhere(['<=', 'date', date("Y-m-d", strtotime(str_replace("/", ".", $this->date_to)))]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'preview', $this->preview]);

		//die($query->createCommand()->getRawSql());
			
			
        return $dataProvider;
    }
	
}
