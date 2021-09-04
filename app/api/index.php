<?php 
    session_start();
    include ("helper_functions.php");

    function getCurrentUri(){
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

        $base_url = getCurrentUri();
        $routes = array();
        $routes = explode('/', $base_url);
        foreach($routes as $index=>$route)
        {
            if(trim($route) != ''){
                $routes[$index] = $route;
            }
        }

        $menu = "404";
        $submenu = "";
        if(sizeof($routes[1]) > 0){
            require_once(__DIR__.'/../../data/api/config.php');

            $menu = $routes[1];

            switch (strtolower($menu)) {
                case 'auth':
                    require_once("auth.php");
                    switch ($routes[2]) {
                        case 'login':
                             $auth = new Auth();
                             $auth->login();
                            break;
                        case 'logout':
                            $auth = new Auth();
                            $auth->logout();
                            break;
                        case 'register':
                            $auth = new Auth();
                            $auth->register();
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'chat':
                    require_once(__DIR__.'/../../data/api/dbconnect.php');
                    require_once(__DIR__.'/../../data/api/chat.php');

                    $chat = new Chat();

                    switch ($routes[2]) {
                        case 'create':
                            
                            $postdata = file_get_contents("php://input");
                            $request = json_decode($postdata);
                            print_r($request);
                            $sqlConnection = Database::Connect();
                            $name = HFtn::escapeInput($request->name, $sqlConnection);
                            $alliances = HFtn::escapeInput($request->alliances, $sqlConnection);
                            $world = HFtn::escapeInput($request->world, $sqlConnection);
                            $timezone = HFtn::escapeInput($request->timezone, $sqlConnection);
                            $price = HFtn::escapeInput($request->price, $sqlConnection);
                            $userColor = HFtn::escapeInput($request->textcolor, $sqlConnection);

                            $chat->createChat($name, $alliances, $world, $timezone, $price, $userColor);
                        break;
                        case 'users':
                            require_once(__DIR__.'/../../data/api/member.php');

                            $postdata = file_get_contents("php://input");
                            $request = json_decode($postdata);

                            $sqlConnection = Database::Connect();
                            $chatId = HFtn::escapeInput($request->chatid, $sqlConnection);

                            $users = $chat->getUsersByChatId($chatId);

                            HFtn::JSONFormatOutput($users);
                        break;

                        case 'usercount':
                            require_once(__DIR__.'/../../data/api/member.php');

                            $postdata = file_get_contents("php://input");
                            $request = json_decode($postdata);

                            $sqlConnection = Database::Connect();
                            $chatId = HFtn::escapeInput($request->chatid, $sqlConnection);

                            $users = $chat->getUsersByChatId($chatId);

                            $userCount = ["count" => sizeof($users)];

                            HFtn::JSONFormatOutput($userCount);
                        break;

                        case 'delete':
                        break;

                        case 'get':
                            require_once(__DIR__.'/../../data/api/member.php');
                            $member = new Member();
                            $memberId = $member->getId($_SESSION['username']);

                            $chats = $chat->getChatsByMember($memberId);

                            HFtn::JSONFormatOutput($chats);
                        break;
                    }

                    break;
                case 'user':
                    require_once(__DIR__.'/../../data/api/dbconnect.php');
                    require_once(__DIR__.'/../../data/api/users.php');

                    switch ($routes[2]) {
                        case 'create':
                            $user = new User();
                            $postdata = file_get_contents("php://input");
                            $request = json_decode($postdata);
                            print_r($request);
                            $sqlConnection = Database::Connect();
                            $name = HFtn::escapeInput($request->name, $sqlConnection);
                            $alliance = HFtn::escapeInput($request->alliance, $sqlConnection);
                            $nameColor = HFtn::escapeInput($request->nameColor, $sqlConnection);
                            $textColor = HFtn::escapeInput($request->textColor, $sqlConnection);
                            $user->create($name, $alliance, $nameColor, $textColor);
                        break;

                        case 'delete':
                        break;
                    }

                break;
                case 'register':
                    include ("page/features.php");
                    break;
                case 'faq':
                    require_once(__DIR__.'/../../data/api/faq.php');
                    $faq = new FAQ();
                    HFtn::JSONFormatOutput($faq->get());
                    
                    break;
                case 'member':
                    switch ($routes[2]) {
                        case 'get':

                            $data = [];
                            $data['code'] = 1;

                            if(isset($_SESSION['username']) && $_SESSION['username'] != ""){
                                $data['username'] = $_SESSION['username'];
                                $data['code'] = 0;
                            }
                            HFtn::JSONFormatOutput($data);
                        break;
                        
                        default:
                            break;
                    }
                    break;
                case 'world':
                    include (root_dir."/../data/api/world.php");
                    $world = new World();
                    HFtn::JSONFormatOutput($world->get($_SESSION['username']));
                    break;
                
                default:
                    //404
                    echo "404";
                    //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                    break;
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        }
        
        if(isset($routes[2])){
            $submenu = $routes[2];
        }

        ?>