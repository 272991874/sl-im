<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace App\Model\Dao;

use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Eloquent\Builder;

/**
 * Class UserDao
 * @package App\Model\Dao
 * @Bean()
 */
class UserDao
{
    /**
     * @Inject()
     * @var User
     */
    protected $userEntity;

    public function findUserInfoById(int $userId){
        return $this->userEntity::whereNull('deleted_at')->find($userId);
    }

    public function findUserInfoByEmail(string $email){
        return $this->userEntity::where('email','=',$email)->first();
    }

    public function createUser(array $data){
        return $this->userEntity::insert($data);
    }

    public function getUserByIds(array $ids){
        return $this->userEntity::whereNull('deleted_at')
            ->whereIn('user_id',$ids)
            ->get();
    }

    public function getRecommendedFriend(int $limit){
        return $this->userEntity::whereNull('deleted_at')
            ->orderBy('created_at','desc')
            ->limit($limit)
            ->get();
    }

    public function searchFriend(string $keyword, int $page, int $size){
        return $this->userEntity::whereNull('deleted_at')
            ->where(function (Builder $builder) use ($keyword) {
                $builder->where('user_id','=',$keyword)
                    ->orWhere('username','like',"%$keyword%")
                    ->orWhere('email','like',"%$keyword%");
            })
            ->paginate($page,$size);
    }
}
