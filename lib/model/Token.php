<?php
/**
 *
 *
 *
 * Token class
 *
 * @author Maxime Picaud
 * @since 26 août 2010
 */
class Token extends BaseToken
{
  const STATUS_ACCESS = 'access';
  const STATUS_REQUEST = 'request';

  public function toOAuthToken()
  {
    return new OAuthToken($this->getTokenKey(), $this->getTokenSecret());
  }

  public function isValidToken()
  {
    $key = $this->getTokenKey();

    $valid = $this->getStatus() == self::STATUS_ACCESS && !empty($key) && !$this->isExpired();


    if($this->getOAuthVersion() == 1 && $valid)
    {
      $secret = $this->getTokenSecret();

      $valid = !empty($secret);
    }

    return $valid;
  }

  public function isExpired()
  {
    return !is_null($this->getExpire()) && $this->getExpire() < time();
  }

  public function refreshToken()
  {
    sfOAuth::refresh($this);
  }

  public function getParams()
  {
    $params = parent::getParams('params');

    return (array) json_decode($params);
  }

  public function getParam($key, $default = null)
  {
    $params = $this->getParams();

    return isset($params[$key])?$params[$key]:$default;
  }

  public function setParams($params)
  {
    parent::setParams(json_encode($params));
  }

  public function setParam($key, $value)
  {
    $params = $this->getParams();
    $params[$key] = $value;

    $this->setParams($params);
  }

  public static function getAllStatuses()
  {
    return array(self::STATUS_REQUEST, self::STATUS_ACCESS);
  }


    /**
     * ?
     */
    public function getUser()
    {
        return $this->getsfGuardUser();
    }

}
