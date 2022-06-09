<?php

namespace App\Controllers;
use App\Models;

class Administration extends BaseController
{
    function __construct() {
        $this->pageHeader();
    }

    function main($adminPage,$option=null) {
        $menu=array(
            array(
                "href"=>"/admin/general",
                "page"=>["general"],
                "label"=>"Général",
                "icon"=>"tools"
            ),
            array(
                "href"=>"/admin/authentification",
                "page"=>["authentification"],
                "label"=>"Authentification",
                "icon"=>"id card"
            ),
            array(
                "href"=>"/admin/groups",
                "page"=>["groups","group"],
                "label"=>"Groupes",
                "icon"=>"users"
            ),
            array(
                "href"=>"/admin/users",
                "page"=>["users","user"],
                "label"=>"Utilisateurs",
                "icon"=>"user"
            ),
            array(
                "href"=>"/admin/acl",
                "page"=>["acl"],
                "label"=>"ACL",
                "icon"=>"user lock"
            ),
            array(
                "href"=>"/admin/rundeck",
                "page"=>["rundeck"],
                "label"=>"Rundeck",
                "icon"=>"stream"
            ),
            array(
                "href"=>"/admin/menu",
                "page"=>["menu"],
                "label"=>"Editer Menu",
                "icon"=>"ellipsis horizontal"
            )
        );
        switch ($adminPage) {
            case "general":
                $model = new Models\DbParameters();
                $modelDm = new Models\DbDataManager();
                $pages=$modelDm->select('name')->findAll();
                $data=array(
                    "name"=>$model->where("name", "name")->first()['value'],
                    "theme"=>$model->where("name", "theme")->first()['value'],
                    "defaultPage"=>$model->where("name", "defaultPage")->first()['value'],
                    "pages"=>$pages
                );
                break;
            case "authentification":
                $model = new Models\DbParameters();
                $ldap=$model->select('name, value')->where("name LIKE 'ldap%'")->findAll();
                foreach($ldap as $ldapParameter) {
                    $data[$ldapParameter['name']]=$ldapParameter['value'];
                }
                break;
            case "groups":
                $modelGrp = new Models\DbGroups();
                $modelRole = new Models\DbRoles();
                $groups=$modelGrp->findAll();
                foreach($groups as $key=>$group) {
                    $groups[$key]['users']=array();
                    foreach($modelRole->where("groupname", $group['groupname'])->findAll() as $user) {
                        array_push($groups[$key]['users'],$user['username']);
                    }
                }
                $data=array("groups"=>$groups);
                break;
            case "group":
                $modelUsr = new Models\DbUsers();
                $modelGrp = new Models\DbGroups();
                $modelRole = new Models\DbRoles();
                $users=$modelUsr->select('username')->findAll();
                $group=$modelGrp->where("groupname",$option)->first();
                $groups=array_flatten_with_dots($modelGrp->select('groupname')->findAll());
                $userRoles=array_flatten_with_dots($modelRole->select('username')->where("groupname",$option)->findAll());
                $data=array(
                    "users"=>$users,
                    "group"=>$group,
                    "groups"=>$groups,
                    "userRoles"=>$userRoles
                );
                break;
            case "user":
                $modelUsr = new Models\DbUsers();
                $user=$modelUsr->where("username",$option)->first();
                $users=array_flatten_with_dots($modelUsr->select('username')->findAll());
                $modelGrp = new Models\DbGroups();
                $groups=$modelGrp->select('groupname')->findAll();
                $modelRole = new Models\DbRoles();
                $userRoles=array_flatten_with_dots($modelRole->select('groupname')->where("username",$option)->findAll());
                $data=array(
                    "users"=>$users,
                    "user"=>$user,
                    "groups"=>$groups,
                    "userRoles"=>$userRoles
                );
                break;
            case "users":
                $modelUsr = new Models\DbUsers();
                $users=$modelUsr->findAll();
                $modelGrp = new Models\DbGroups();
                $groups=$modelGrp->select('groupname')->findAll();
                $modelParameters = new Models\DbParameters();
                $ldap=boolval($modelParameters->select('value')->where("name","ldapEnable")->first()['value'])??false;
                $modelRole = new Models\DbRoles();
                foreach($users as $key=>$user) {
                    $users[$key]['groups']=array();
                    foreach($modelRole->where("username", $user['username'])->findAll() as $group) {
                        array_push($users[$key]['groups'],$group['groupname']);
                    }
                }
                $data=array(
                    "users"=>$users,
                    "ldap"=>$ldap,
                    "groups"=>$groups,
                );
                break;
            case "acl":
                $modelGrp = new Models\DbGroups();
                $modelUsr = new Models\DbUsers();
                $modelAcl = new Models\DbAcl();
                $model = new Models\DbDataManager();
                $users=$modelUsr->select('username')->findAll();
                $groups=$modelGrp->select('groupname')->findAll();
                $acl=$modelAcl->findAll();
                $pages=$model->select('name')->findAll();
                foreach($acl as $key=>$pageacl) {
                    $acl[$pageacl['page']]['users']=json_decode($pageacl['users'],true)??array();
                    $acl[$pageacl['page']]['groups']=json_decode($pageacl['groups'],true)??array();
                    unset($acl[$key]);
                }
                $data=array(
                    "users"=>$users,
                    "groups"=>$groups,
                    "pages"=>$pages,
                    "acl"=>$acl
                );
                break;
            case "menu":
                $model = new Models\DbDataManager();
                $pages=$model->select('name')->findAll();
                $data=array(
                    "pages"=>$pages,
                );
                break;
            default:
                $data=array();
        }
        echo view("templates/parameters_header",array("menu"=>$menu,'page'=>$adminPage));
        echo view("admin/$adminPage",$data);
        echo view("templates/footer");
    }

    function editor($name = null,$type = null) {
        switch($type) {
            case "source":
                $data=array(
                    "input"=>$name,
                    "types"=>['json','yaml','xml','mysql','sqlite','postgres']
                );
                break;
            case "item":
                $model = new Models\DbDataManager();
                $result = $model->where('name', $name)->first();
                $sources=array();
                if (isset($result['source'])) {
                    $result = json_decode($result['source'],true);
                    foreach($result['sources'] as $source) {
                        array_push($sources,$source['name']);
                    }
                }
                $color = ['red','orange','yellow','olive','green','teal','blue','violet','purple','pink','brown','grey','black'];
                $colorVariant = ['',' basic'];
                $colours=array();
                foreach ($color as $c) {
                    foreach ($colorVariant as $v) {
                        array_push($colours,"$c$v");
                    }
                }
                $data=array(
                    "input"=>$name,
                    "colours"=>$colours,
                    "conditions"=>['==','!=','>=','<=','>','<','IN','NOT IN'],
                    "sources"=>$sources
                );
                break;
            case "presentation":
                $model = new Models\DbDataManager();
                $result = $model->where('name', $name)->first();
                $types=['separator'];
                $items=array();
                if (isset($result['item'])) {
                    array_push($types,"items");
                    $result = json_decode($result['item'],true);
                    foreach($result['items'] as $item) {
                        array_push($items,$item['title']['label']);
                    }
                }
                $pages=array_diff(scandir(APPPATH."Views/pages"),array('.','..'));
                if (!empty($pages)) {
                    array_push($types,"page");
                }
                $data=array(
                    "input"=>$name,
                    "types"=>$types,
                    "items"=>$items,
                    "pages"=>$pages,
                );
                break;
        }

        echo view("templates/editor_header",$data);
        echo view("admin/editor_$type",$data);
        echo view("templates/parameters_footer");
        echo view("templates/footer");
    }

    function editorSelector() {
        $model = new Models\DbDataManager();
        $result = $model->orderBy('name', 'ASC')->findAll();
        $data = array(
            "data"=> $result
        );
        echo view("admin/editor_selector",$data);
        echo view("templates/footer");
    }

    public function profile()
    {
        $modelUsr = new Models\DbUsers();
        $user=$modelUsr->where("username",session()->get("username"))->first();
        $modelRole = new Models\DbRoles();
        $userRoles=array_flatten_with_dots($modelRole->select('groupname')->where("username",session()->get("username"))->findAll());
        $data=array(
            "user"=>$user,
            "userRoles"=>$userRoles
        );
        echo view("profile.php",$data);
        echo view("templates/footer.php");
    }
}