使用方法:
1. 建立视图钩子 名字为 UploadImages
2. 启用插件
3. 修改 \Application\Admin\Common\function.php
    135行  get_attribute_type函数的type 增加
        'pictures'   =>  array('上传多图','varchar(255) NOT NULL'),
4. 修改
    \Application\Admin\View\Article\Add.html
    \Application\Admin\View\Article\Edit.html
   两个文件
    在
        <switch name="field.type">
    之后添加
        <case value="pictures">
            {:hook('UploadImages', array('name'=>$field['name'],'value'=>$field['value']))}
        </case>
5. 对你想要增加多图上传的模型  增加字段  类型为 多图上传
6. 前台调用多图时 取了相应字段以后  implode(',',字段名) 以后  循环输出 {$v|get_cover='path'}
