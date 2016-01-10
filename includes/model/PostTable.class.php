<?php

class PostTable extends Table
{
  private static $instance = null;
  private static $POST_TYPE_VIDEO = "video";
  private static $POST_TYPE_ARTICLE = "article";
  private static $PAGE_SIZE = 50;
  public static $TABLE = 'post';
  public static $TABLE_POST_TAG = 'post_tag';
  public static $TABLE_POST_CAT = 'post_category';

  protected function  __construct() {
    parent::__construct();
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new PostTable();
    }
    return self::$instance;
  }

  /**
   * Return an Post record as an assoc array
   * @param <int> $Id The post id
   * @return <Post> The post with that id or null
   */
  public function getPostById($id) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE id = $id LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Convert a post array into a post object
   * @param array $aRow 
   * @return Post
   */
  public function arrayToObject($aRow) {
    if (empty($aRow)) {
      return null;
    }
    $post = new Post();
    $post->setId($aRow['id']);
    $post->setPostId($aRow['post_id']);
    $post->setPostType($aRow['post_type']);
    $post->setTitle($aRow['title']);
    $post->setPostUrl($aRow['post_url']);
    $post->setProvider($aRow['provider']);
    $post->setVideoUrl($aRow['video_url']);
    $post->setPostedOn($aRow['posted_on']);
    $post->setVideoThumbUrl($aRow['video_thumb_url']);
    return $post;
  }
  
  /**
   * Returns Posts
   * @return array<Post>
   */
  public function getPosts($type, $pageNum, $pageSize) {
    $where = "";
    $limit = "";
    
    if ($type != self::$POST_TYPE_ARTICLE && $type != self::$POST_TYPE_VIDEO) {
        $where = "";
    } else {
        $where = " WHERE post_type = '$type' ";
    }
    
    if (empty($pageSize)) {
        $pageSize = self::$PAGE_SIZE;
    } else {
        $pageSize = intval($pageSize);
    }
    
    if ($pageNum !== null) {
        $limit = " LIMIT " . intval($pageNum) * $pageSize . ", $pageSize ";
    } 
    
    $query = "SELECT *
                FROM " . self::$TABLE .  
                $where .
                " ORDER BY id desc" .
                $limit;
    
    $postsRS = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($postsRS)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Search Posts
   * @param string searchString . The string to search posts by
   * @return array<Post>
   */
  public function searchPosts($searchString, $pageNum, $pageSize) {
    $where = " WHERE (title LIKE '%$searchString%' OR posted_on LIKE '%$searchString%')";
    $limit = "";
    
    if (empty($pageSize)) {
        $pageSize = self::$PAGE_SIZE;
    } else {
        $pageSize = intval($pageSize);
    }
    
    if ($pageNum !== null) {
        $limit = " LIMIT " . intval($pageNum) * $pageSize . ", $pageSize ";
    }
    
    $queryFromPost = "SELECT *
                FROM " . self::$TABLE .  
                $where; 
    
    $queryFromTag = "SELECT p.* FROM " . self::$TABLE . " AS p 
                        INNER JOIN (SELECT pt.post_id FROM " . self::$TABLE_POST_TAG . " AS pt 
                                        INNER JOIN " . TagTable::$TABLE . " AS t ON pt.tag_id= t.id 
                                        WHERE t.name LIKE '%$searchString%') AS tag
                        ON p.id = tag.post_id";
    
    $queryFromCategory = "SELECT p2.* FROM " . self::$TABLE . " AS p2 
                        INNER JOIN (SELECT pc.post_id FROM " . self::$TABLE_POST_CAT . " AS pc 
                                        INNER JOIN " . CategoryTable::$TABLE . " AS c ON pc.cat_id= c.id 
                                        WHERE c.name LIKE '%$searchString%') AS category
                        ON p2.id = category.post_id";
    
    $query = "SELECT DISTINCT * FROM 
                ($queryFromPost UNION $queryFromTag UNION $queryFromCategory) AS result
                ORDER BY result.id desc" .
                $limit;
    
    $postsRS = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($postsRS)) {
      array_push($result, $this->arrayToObject($row));
    }
   
    return $result;
  }
  
  /**
   * Get all post of type = video
   * Returns Video Posts
   * @return array<Post>
   */
  public function getVideoPosts() {
    $query = "SELECT *
                FROM " . self::$TABLE . " 
                WHERE post_type = '" . self::$POST_TYPE_VIDEO . "'
                ORDER BY id desc";
    
    $postsRS = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($postsRS)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Returns all the tags from a specific post
   * @param int $postId
   * @return array<Tag>
   */
  public function getPostTags($postId) {
    if (empty($postId)) {
      return null;
    }
    
    $query = "SELECT t.*
                FROM " . self::$TABLE . " AS p
                INNER JOIN " . self::$TABLE_POST_TAG . " AS pt ON p.id = pt.post_id 
                INNER JOIN " . TagTable::$TABLE . " AS t ON pt.tag_id = t.id 
                WHERE p.id = $postId";
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
        array_push($result, TagTable::getInstance()->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Returns all the tags from a list of posts
   * @param array<int> $postsIds
   * @return array<Tag>
   */
  public function getPostsTags($postsIds) {
    if (empty($postsIds)) {
      return null;
    }
    
    $posts = implode(', ', $postsIds);
    
    $query = "SELECT p.id as post_id, t.*
                FROM " . self::$TABLE . " AS p
                INNER JOIN " . self::$TABLE_POST_TAG . " AS pt ON p.id = pt.post_id 
                INNER JOIN " . TagTable::$TABLE . " AS t ON pt.tag_id = t.id 
                WHERE p.id IN ($posts) 
                ORDER BY post_id";
    
    $rs = parent::executeQuery($query);
    $result = array();
    
    while ($row = mysql_fetch_assoc($rs)) {
        $postId  = $row['post_id'];
        $tag = TagTable::getInstance()->arrayToObject($row);
        
        if (!empty($result[$postId])) {
            $result[$postId][] = $tag;
        } else {
            $result[$postId] = array($tag);
        }
    } 
    return $result;
  }
  
  /**
   * Returns all the categories from a specific post
   * @param int $postId
   * @return array<Category>
   */
  public function getPostCategories($postId) {
    if (empty($postId)) {
      return null;
    }
    
    $query = "SELECT c.*
                FROM " . self::$TABLE . " AS p
                INNER JOIN " . self::$TABLE_POST_CAT . " AS pc ON p.id = pc.post_id
                INNER JOIN " . CategoryTable::$TABLE . " AS c ON pc.cat_id = c.id
                WHERE p.id = $postId";
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
      array_push($result, CategoryTable::getInstance()->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Returns all the categories from a list of posts
   * @param array<int> $postsIds
   * @return array<Category>
   */
  public function getPostsCategories($postsIds) {
    if (empty($postsIds)) {
      return null;
    }
    
    $posts = implode(', ', $postsIds);
    
    $query = "SELECT p.id as post_id, c.*
                FROM " . self::$TABLE . " AS p
                INNER JOIN " . self::$TABLE_POST_CAT . " AS pc ON p.id = pc.post_id 
                INNER JOIN " . CategoryTable::$TABLE . " AS c ON pc.cat_id = c.id 
                WHERE p.id IN ($posts) 
                ORDER BY post_id";
    
    $rs = parent::executeQuery($query);
    $result = array();
    
    while ($row = mysql_fetch_assoc($rs)) {
        $postId  = $row['post_id'];
        $cat = CategoryTable::getInstance()->arrayToObject($row);
        
        if (!empty($result[$postId])) {
            $result[$postId][] = $cat;
        } else {
            $result[$postId] = array($cat);
        }
    } 
    return $result;
  }
  
  /**
   * Get all the pots of type video where id is greater than the id as param.
   * Use it for refresh purpose
   * 
   * 
   * @param long lastId . This is the id used as minimun of the posts to retrieve
   * @return array<Post>
   */
  public function getNewVideoPosts($lastId) {
    $query = "SELECT *
                FROM " . self::$TABLE . " 
                WHERE post_type = '" . self::$POST_TYPE_VIDEO . "' 
                  AND id > $lastId
                ORDER BY id desc";
    
    $postsRS = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($postsRS)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Get all the posts where its id are greater than the lastId as param.
   * Use it for refresh purpose
   * 
   * 
   * @param long lastId . This id is used as the base of new posts to retrieve
   * @return array<Post>
   */
  public function getNewPosts($lastId) {
    $query = "SELECT *
                FROM " . self::$TABLE . " 
                WHERE id > $lastId
                ORDER BY id desc";
    
    $postsRS = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($postsRS)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Get the maximun and minimun id from posts
   * @return array
   */
  public function getMaxMinIdPosts() {
      $query = "SELECT MAX(id) AS 'max', MIN(id) as 'min'
                FROM " . self::$TABLE;
    
    $resultSet = parent::executeQuery($query);
    return parent::getRecordAsArray($resultSet);
  }
  
  /**
   *Return the post_id from the last post in the bd (last inserted)
   * @return int
   */
  public function getLastPost_PostId() {
    $query = "SELECT id, post_id FROM " . self::$TABLE . "     
            ORDER BY id DESC LIMIT 1";
    $resultSet = parent::executeQuery($query);
    $resultArray = parent::getRecordAsArray($resultSet);
    return $resultArray['post_id'];
  }

  /**
   * Inserts posts into this Table
   * @param <Post> | array<Post> $posts
   * @return <array> agency as assoc array
   */
  
  public function save($posts) {
    if (!empty($posts)) {
      $query = "INSERT INTO " . self::$TABLE . " (
                id, 
                post_id, 
                post_type, 
                title, 
                post_url, 
                provider,
                video_url, 
                posted_on, 
                video_thumb_url) VALUES";
      
      if (is_array($posts)) {
        //make a bulk insert - THIS INSERT METHOD DO NOT PERSIST TAGS AND CATEGORIES FROM POSTS
        $rowsValues = "";
        foreach ($posts as $post) {
          if ($post->isValid()) {
            $rowsValues .= self::populateRowValuesFromPostObject($post) . ", ";
          }
        }
        
        if (!empty($rowsValues)) {
          $rowsValues = substr($rowsValues, 0, strlen($rowsValues) - 2);

          $query .= $rowsValues . ";";
          parent::executeQuery($query);
        }
      } else {
        //make a single row insert
        if ($posts->isValid()) {
          $query .= self::populateRowValuesFromPostObject($posts) . ";";
          parent::executeQuery($query);
          $posts->setId($this->lastInsertID());
          $this->savePostTags($posts);
          $this->savePostCats($posts);
          return $posts;
        }
      }
    }
    
    return null;
  }
  
  /**
   * Insert all the tags linked with this post in post_tag table
   * @param Post $post 
   */
  public function savePostTags($post) {
    $tags = $post->getTags();
    if (!empty($tags)) {
      $rowsValues = "";
      $query = "INSERT INTO " . self::$TABLE_POST_TAG . "(post_id, tag_id) VALUES ";
      foreach ($tags as $tag) {
        if ($tag->isValid() && $post->isValid()) {
          $rowsValues .= "(" . $post->getId() . ", " . $tag->getId() . "), ";
        }
      }
      
      if (!empty($rowsValues)) {
        $rowsValues = substr($rowsValues, 0, strlen($rowsValues) - 2);
        $query .= $rowsValues . ";";
        parent::executeQuery($query);
      }
    }
  }
  
  /**
   * Insert all the cats linked with this post in post_category table
   * @param Post $post 
   */
  public function savePostCats($post) {
    $cats = $post->getCats();
    if (!empty($cats)) {
      $rowsValues = "";
      $query = "INSERT INTO " . self::$TABLE_POST_CAT . "(post_id, cat_id) VALUES ";
      foreach ($cats as $cat) {
        if ($cat->isValid() && $post->isValid()) {
          $rowsValues .= "(" . $post->getId() . ", " . $cat->getId() . "), ";
        }
      }
      
      if (!empty($rowsValues)) {
        $rowsValues = substr($rowsValues, 0, strlen($rowsValues) - 2);
        $query .= $rowsValues . ";";
        parent::executeQuery($query);
      }
    }
  }
  
  /**
   * Populates the VALUES section for an INSERT sql statement from a Post object
   * @param <Post> $post
   * @return string 
   */
  private function populateRowValuesFromPostObject($post) {
    $rowValue = "(" . $post->getId() . ", "
              . $post->getPostId() . ",'"
              . $post->getPostType() . "','"
              . $post->getTitle() . "','"
              . $post->getPostUrl() . "','"
              . $post->getProvider() . "','"
              . $post->getVideoUrl() . "','"
              . $post->getPostedOn() . "','"
              . $post->getVideoThumbUrl() . "')";
    return $rowValue;
  }
}

?>