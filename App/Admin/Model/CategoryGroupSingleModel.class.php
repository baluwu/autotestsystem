<?php
namespace Admin\Model;

use Think\Model;

//用例组分类
class CategoryGroupSingleModel extends Model {

    //获取分类数据
    public function getCategoryTreeData()
    {
        $ret = array(
            array(
                'pid'=>'0',
                'id'=>'1',
                'text'=>'一级菜单1',
                'child'=>
                    array(
                        'pid'=>'1',
                        'id'=>'11',
                        'text'=>'二级菜单1',
                        'child'=>array(),
                    ),
                    array(
                        'pid'=>'1',
                        'id'=>'12',
                        'text'=>'二级菜单2',
                        'child'=>array(),
                    ),
                    array(
                        'pid'=>'1',
                        'id'=>'13',
                        'text'=>'二级菜单3',
                        'child'=>array(),
                    )
            ),

            array(
                'pid'=>'0',
                'id'=>'2',
                'text'=>'一级菜单1',
                'child'=>
                    array(
                        'pid'=>'2',
                        'id'=>'21',
                        'text'=>'二级菜单1',
                        'child'=>array(),
                    ),
                array(
                    'pid'=>'2',
                    'id'=>'22',
                    'text'=>'二级菜单2',
                    'child'=>array(),
                ),
                array(
                    'pid'=>'2',
                    'id'=>'23',
                    'text'=>'二级菜单3',
                    'child'=>array(),
                )
            ),

            array(
                'pid'=>'0',
                'id'=>'3',
                'text'=>'一级菜单1',
                'child'=>
                    array(
                        'pid'=>'3',
                        'id'=>'31',
                        'text'=>'二级菜单1',
                        'child'=>array(),
                    ),
                array(
                    'pid'=>'3',
                    'id'=>'32',
                    'text'=>'二级菜单2',
                    'child'=>array(),
                ),
                array(
                    'pid'=>'3',
                    'id'=>'33',
                    'text'=>'二级菜单3',
                    'child'=>array(),
                )
            ),
        );

        return $ret;
    }

    //添加分类
    public function addCategory()
    {

    }

    //保存分类
    public function saveCategory()
    {

    }
}
