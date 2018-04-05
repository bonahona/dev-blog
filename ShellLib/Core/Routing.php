<?php
const VARIABLE = 0;
const VALUE = 1;
const ELLIPSIS = 2;

class Routing
{
    private $RoutingConfig;

    // Cache variables and the likes so they dont have to be extracted multiple times
    private $RoutingDescription;

    public function __construct($config = null)
    {
        $this->RoutingConfig = $config;
        $this->RoutingDescription = array();
    }

    public function ParseUrl($requestRoot, $requestUrl)
    {
        $requestPath = explode('/', $requestRoot);

        // Remove only the last part of the string
        $requestRoot = str_replace(end($requestPath), '', $requestRoot);


        // If the request root is the root, there's nothing to clear out
        if($requestRoot != '/') {
            $requestResource = str_replace($requestRoot, '', $requestUrl);
        }else{
            $requestResource = $requestUrl;
        }

        $requestInfo = RemoveEmpty(explode('/', $requestResource));

        if(isset($this->RoutingConfig['Routes'])){
            foreach($this->RoutingConfig['Routes'] as  $route){
                if($this->CanHandleRequest($route, $requestInfo)){
                    return $this->HandleRequest($route, $requestInfo);
                }
            }
        }

        return null;
    }

    protected function CanHandleRequest($route, $requestInfo)
    {
        // Turn the route path to an array containing only its value members (the first and last element is most likely empty and will be removed)
        $routePath = $this->GetRouteName($route);
        $routePathParts = RemoveEmpty(explode('/', $routePath));

        $routeDescription = $this->GetRoutePathDescription($routePathParts);

        $routeName = $this->GetRouteName($route);
        $routeDescriptionKeys = array_keys($routeDescription);
        foreach($routeDescriptionKeys as $key){
            if(!$this->ValueExists($key, $routeDescription, $requestInfo)){
                return false;
            }
        }

        if(!$this->IsSameLength($routeDescription, $requestInfo)){
            return false;
        }


        // This routing rule can handle the current request path
        $this->RoutingDescription[$routeName] = $routeDescription;

        // This route has not been failed and is viable
        return true;
    }

    protected function HandleRequest($route, $requestInfo)
    {
        $routeName =  $this->GetRouteName($route);

        $routePathDescription = $this->RoutingDescription[$routeName];

        $routeData = $route[$routeName];
        $routeDescription = $this->GetRouteDescription($routeData);
        $variableData = $this->MapPathToVariables($requestInfo, $routePathDescription);

        if($routeDescription['Controller']['Type'] == VALUE){
            $controllerName = $routeDescription['Controller']['Value'];
        }else if($routeDescription['Controller']['Type'] == VARIABLE){
            $controllerName = $this->GetVariableValue($routeDescription['Controller']['Value'], $variableData);
        }

        if($routeDescription['Action']['Type'] == VALUE){
            $actionName = $routeDescription['Action']['Value'];
        }else if($routeDescription['Action']['Type'] == VARIABLE){
            $actionName = $this->GetVariableValue($routeDescription['Action']['Value'], $variableData);
        }

        $variables = array();

        if(isset($routeDescription['Variables']['Type'])) {
            $type = $type = $routeDescription['Variables']['Type'];
            if ($type == VARIABLE) {
                $value = $this->GetVariableValue($routeDescription['Variables']['Value'], $variableData);
                if ($value != "") {
                    $variables[] = $value;
                }
            } else if ($type == VALUE) {
                $variables[] = $routeDescription['Variables']['Value'];
            } else if ($type == ELLIPSIS) {
                foreach ($variableData['EllipsisVariables'] as $ellipsisVariable) {
                    $variables[] = $ellipsisVariable;
                }
            }
        }else{
            // This route variable description has multiple entries
            foreach($routeDescription['Variables'] as $routePathDescriptionVariable){
                if(isset($routePathDescriptionVariable['Type'])){
                    $type = $routePathDescriptionVariable['Type'];
                    if($type == VARIABLE){
                        $value = $this->GetVariableValue($routePathDescriptionVariable['Value'], $variableData);
                        $variables[] = $value;
                    }else if($type == VALUE){
                        $variables[] = $routePathDescriptionVariable['Value'];
                    }else if($type == ELLIPSIS){
                        foreach ($variableData['EllipsisVariables'] as $ellipsisVariable) {
                            $variables[] = $ellipsisVariable;
                        }
                    }
                }
            }
        }

        $result = array(
            'MethodName' => $_SERVER['REQUEST_METHOD'],
            'ControllerName' => $controllerName,
            'ActionName' => $actionName,
            'Variables' => $variables
        );

        return $result;
    }

    public function GetRoutePathDescription($routePathParts)
    {
        $result = array();
        foreach($routePathParts as $routePart){
            if($this->IsEllipsis($routePart)){
                $result[] = array(
                    'Type' => ELLIPSIS,
                    'Value' => null
                );
                // Ellipsis are always the end of a route no matter if anything follows
                return $result;
            }else if($this->IsVariable($routePart)){
                $variableName = $this->GetVariableName($routePart);
                $result[] = array(
                    'Type' => VARIABLE,
                    'Value' => $variableName,
                    'DefaultExists' => ($this->GetDefaultValue($variableName) != null)
                );
            }else{
                $result[] = array(
                    'Type' => VALUE,
                    'Value' => $routePart
                );
            }
        }

        return $result;
    }

    public function GetRouteDescription($routeData)
    {
        $result = array();

        if(!isset($routeData['Controller'])){
            trigger_error('Missing controller setting in route description', E_USER_ERROR);
        }

        if(!isset($routeData['Action'])){
            trigger_error('Missing action setting in route description', E_USER_ERROR);
        }

        $result['Controller'] = $this->DescribeRouteSection($routeData['Controller'], false);
        $result['Action'] = $this->DescribeRouteSection($routeData['Action'], false);

        if(isset($routeData['Variables'])){
            $result['Variables'] = $this->DescribeRouteSection($routeData['Variables'], true);
        }

        return $result;
    }

    public function DescribeRouteSection($routeDataSection, $allowEllipsis)
    {
        $result = array();

        if(is_array($routeDataSection)){
            foreach($routeDataSection as $subSection){
                $result[] = $this->DescribeRouteSection($subSection, true);
            }

            return $result;
        }
        if(!$allowEllipsis && $this->IsEllipsis($routeDataSection)){
            trigger_error('Ellipsis is not a valid controller value', E_USER_ERROR);
            return;
        }else if($allowEllipsis && $this->IsEllipsis($routeDataSection)){
            $result = array(
                'Type' => ELLIPSIS,
                'Value' => null
            );
        }else if($this->IsVariable($routeDataSection)){
            $value = $this->GetVariableName($routeDataSection);
            $result = array(
                'Type' => VARIABLE,
                'Value' => $value
            );
        } else{
            $result = array(
                'Type' => VALUE,
                'Value' => $routeDataSection
            );
        }

        return $result;
    }

    public function ValueExists($key, $routeDescription, $requestInfo)
    {
        // Make sure the value exists and that it's the same as expected
        if($routeDescription[$key]['Type'] == VALUE){
            if(!isset($requestInfo[$key])){
                return false;
            }

            $routeDescriptionName = $routeDescription[$key]['Value'];
            if(strtolower($routeDescriptionName) != strtolower($requestInfo[$key])){
                return false;
            }

        }else if($routeDescription[$key]['Type'] == VARIABLE){
            if(!isset($requestInfo[$key]) && !$routeDescription[$key]['DefaultExists']){
                return false;
            }
        }else if($routeDescription[$key]['Type'] == VARIABLE){
            return true;
        }

        return true;
    }

    public function IsSameLength($routeDescription, $requestInfo)
    {
        $lastRouteRule = end($routeDescription);
        if($lastRouteRule['Type'] == ELLIPSIS){
            return true;
        }

        if(count($routeDescription) != count($requestInfo)){
            return false;
        }

        return true;
    }

    public function GetDefaultValue($variableName)
    {
        if(!isset($this->RoutingConfig['Default'])){
            return null;
        }

        if(!isset($this->RoutingConfig['Default'][$variableName])){
            return null;
        }else{
            return $this->RoutingConfig['Default'][$variableName];
        }
    }

    // Variables starts and ends with curly brackets
    protected function IsVariable($part)
    {
        return (startsWith($part, '{') && endsWith($part, '}'));
    }

    protected function IsEllipsis($part)
    {
        return $part == '{...}';
    }

    protected function GetVariableName($routeInfoPart)
    {
        $length = strlen($routeInfoPart);
        return substr($routeInfoPart, 1, $length -2);
    }

    protected function MapPathToVariables($requestInfo, $routePathDescription)
    {
        $currentIndex = 0;
        $requestInfoCount = count($requestInfo);
        $ellipsisFound = false;
        $variables = array();
        $ellipsisVariables = array();

        while($currentIndex < $requestInfoCount){
            if($ellipsisFound){
                $ellipsisVariables[] = $requestInfo[$currentIndex];
            }else {
                if (isset($routePathDescription[$currentIndex])) {
                    $currentElement = $routePathDescription[$currentIndex];
                    if ($currentElement['Type'] == VARIABLE) {
                        $variables[$currentElement['Value']] = $requestInfo[$currentIndex];
                    } else if ($currentElement['Type'] == ELLIPSIS) {
                        $ellipsisFound = true;
                        $ellipsisVariables[] = $requestInfo[$currentIndex];
                    }
                }
            }

            $currentIndex ++;
        }

        return array(
            'Variables' => $variables,
            'EllipsisVariables' => $ellipsisVariables
        );
    }

    protected function GetVariableValue($variableName, $variableData)
    {
        if(isset($variableData['Variables'][$variableName])) {
            $result = $variableData['Variables'][$variableName];
        }else{
            $result = $this->GetDefaultValue($variableName);
        }

        return $result;
    }

    protected function GetRouteName($routeInfo) {
        return array_keys($routeInfo)[0];
    }
}