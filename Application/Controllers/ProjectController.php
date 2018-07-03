<?php
require_once ('AdminController.php');
class ProjectController extends AdminController
{
    public function Index()
    {
        $this->Title = 'Manage projects';

        $projects = $this->Models->Project->Where(['IsDeleted' => 0]);
        $this->Set('Projects', $projects);

        return $this->View();
    }

    public function Create()
    {
        $this->Title = 'Create project';

        if($this->IsPost()){
            $project = $this->Data->Parse('Project', $this->Models->Project);
            $project->Save();

            return $this->Redirect('/project');
        }else {
            $project = $this->Models->Tag->Create(['IsActive' => 1]);
            $this->Set('Project', $project);

            return $this->View();
        }
    }

    public function Edit($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $project = $this->Models->Project->Find($id);
        if($project == null){
            return $this->HttpNotFound();
        }

        $this->Title = 'Edit project';

        if($this->IsPost()){
            $project = $this->Data->DbParse('Project', $this->Models->Project);
            $project->Save();

            return $this->Redirect('/project');
        }else{
            $this->Set('Project', $project);

            return $this->View();
        }
    }
}