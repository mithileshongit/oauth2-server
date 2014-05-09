<?php

namespace RelationalExample\Storage;

use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Storage\Adapter;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AbstractTokenEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;

use Illuminate\Database\Capsule\Manager as Capsule;

class AccessTokenStorage extends Adapter implements AccessTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        $result = Capsule::table('oauth_access_tokens')
                            ->where('access_token', $token)
                            ->where('expire_time', '>=', time())
                            ->get();

        if (count($result) === 1) {
            $token = new AccessTokenEntity($this->server);
            $token->setExpireTime($result[0]['expire_time']);
            $token->setToken($result[0]['access_token']);

            return $token;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getByRefreshToken(RefreshTokenEntity $refreshToken)
    {
        die(var_dump(__METHOD__, func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AbstractTokenEntity $token)
    {
        $result = Capsule::table('oauth_access_token_scopes')
                                    ->select(['oauth_scopes.id', 'oauth_scopes.description'])
                                    ->join('oauth_scopes', 'oauth_access_token_scopes.scope', '=', 'oauth_scopes.id')
                                    ->where('access_token', $token->getToken())
                                    ->get();

        $response = [];

        if (count($result) > 0) {
            foreach ($result as $row) {
                $scope = new ScopeEntity($this->server);
                $scope->setId($row['id']);
                $scope->setDescription($row['description']);
                $response[] = $scope;
            }
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $sessionId)
    {
        die(var_dump(__METHOD__, func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AbstractTokenEntity $token, ScopeEntity $scope)
    {
        die(var_dump(__METHOD__, func_get_args()));
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AbstractTokenEntity $token)
    {
        die(var_dump(__METHOD__, func_get_args()));
    }
}
