<?php 

namespace Security;

/**
 * Resource library
 */
class Resource {
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		//Create models
		$this->_answer = \App::make('Answer');
		$this->_chapter = \App::make('Chapter');
		$this->_classroom = \App::make('Classroom');
		$this->_classroom_user = \App::make('ClassroomUser');
		$this->_exercise = \App::make('Exercise');
		$this->_part = \App::make('Part');
		$this->_question = \App::make('Question');
		$this->_section = \App::make('Section');
		$this->_user_exercise = \App::make('UserExercise');
		
	}
	
	/**
	 * Check if the user has access to the ressource
	 *
	 * @param User $user
	 * @param int $id
	 * @param string $resource
	 * @return bool
	 */
	public function hasAccess($user, $id, $resource = null)
	{		
		//If we don't have a user
		if(($user instanceof \User) !== true){
			return false;
		}
		
		if(empty($resource)){
			$resource = $this->getResourceFromRoute(\Route::currentRouteName());
		}
		
		//Get the method		
		$method = '_hasAccessTo' . ucfirst($resource);
		
		//If the type is not supported
		if(!method_exists($this, $method)) return false;
		
		//Check access
		return $this->{$method}($user, $id);
	}
	
	/**
	 * Has access to the Answer resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToAnswers($user, $id)
	{
		//Get answer
		list($code, $answer) = $this->_answer->get_entry($id);
		
		//If resource not found
		if(empty($answer[0])){
			return false;
		}
			
		//Get parent exercise
		list($code, $exercise) = $this->_exercise->get_entry($answer[0]->exercise_id);
			
		//Get parent section
		list($code, $section) = $this->_section->get_entry($exercise[0]->section_id);
			
		//Get parent chapter
		list($code, $chapter) = $this->_chapter->get_entry($section[0]->chapter_id);
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);		
	}
	
	/**
	 * Has access to the Chapter resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToChapters($user, $id)
	{
		//Get chapter
		list($code, $chapter) = $this->_chapter->get_entry($id);
		
		//If resource not found
		if(empty($chapter[0])){
			return false;
		}
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);
	}
	
	/**
	 * Has access to the Classroom resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToClassrooms($user, $id)
	{
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $id);
	}
	
	/**
	 * Has access to the ClassroomUser resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToClassroomsusers($user, $id)
	{
		
		//If the user is a student
		if($user->role == \User::ROLE_STUDENT){
			
			//Get the classroom exercise
			$classrooms_id = $this->_classroom_user->find_classrooms_id_from_user($user->id);
			
			//If the classroom id not found into the user classrooms
			if(!in_array($id, $classrooms_id)){
				return false;
			} else {
				return true;
			}			
		}
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $id);
	}
	
	/**
	 * Has access to the Exercise resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToExercises($user, $id)
	{
		//Get exercise
		list($code, $exercise) = $this->_exercise->get_entry($id);
		
		//If resource not found
		if(empty($exercise[0])){
			return false;
		}
			
		//Get parent section
		list($code, $section) = $this->_section->get_entry($exercise[0]->section_id);
			
		//Get parent chapter
		list($code, $chapter) = $this->_chapter->get_entry($section[0]->chapter_id);
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);
	}
	
	/**
	 * Has access to the Part resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToParts($user, $id)
	{
		//Get part
		list($code, $part) = $this->_part->get_entry($id);
		
		//If resource not found
		if(empty($part[0])){
			return false;
		}
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $part[0]->classroom_id);
	}
	
	/**
	 * Has access to the Question resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToQuestions($user, $id)
	{
		//Get question
		list($code, $question) = $this->_question->get_entry($id);
		
		//If resource not found
		if(empty($question[0])){
			return false;
		}
			
		//Get parent exercise
		list($code, $exercise) = $this->_exercise->get_entry($question[0]->exercise_id);
			
		//Get parent section
		list($code, $section) = $this->_section->get_entry($exercise[0]->section_id);
			
		//Get parent chapter
		list($code, $chapter) = $this->_chapter->get_entry($section[0]->chapter_id);
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);
	}
	
	/**
	 * Has access to the Section resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToSections($user, $id)
	{
		//Get section
		list($code, $section) = $this->_section->get_entry($id);
		
		//If resource not found
		if(empty($section[0])){
			return false;
		}
			
		//Get parent chapter
		list($code, $chapter) = $this->_chapter->get_entry($section[0]->chapter_id);
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);
	}
	
	/**
	 * Has access to the UserExercise resource
	 * 
	 * @param User $user
	 * @param int $id
	 * @return bool
	 */
	private function _hasAccessToUsersexercises($user, $id)
	{
		//If the user is a student
		if($user->role == \User::ROLE_STUDENT){
							
			//Get userexercise
			$user_exercises = $this->_user_exercise->get_by_user_and_ids($user->id, array($id));

			//If the exercise is not associate to the user
			if(empty($user_exercises)) return false;			
		}
			
		//Get parent exercise
		list($code, $exercise) = $this->_exercise->get_entry($id);
			
		//Get parent section
		list($code, $section) = $this->_section->get_entry($exercise[0]->section_id);
			
		//Get parent chapter
		list($code, $chapter) = $this->_chapter->get_entry($section[0]->chapter_id);
		
		//Is associate to the classroom
		return $this->isAssociateToClassroom($user, $chapter[0]->classroom_id);
	}
	
	/**
	 * Check if the user is associate to the classroom
	 * 
	 * @param \User $user
	 * @param int $id
	 * @return boolean
	 */
	public function isAssociateToClassroom(\User $user, $id)
	{			
		
		//Depending the user role
		switch($user->role){
			
			default:
				return false;
				break;
			
			case \User::ROLE_GUEST:
				return false;
				break;
			
			//curently not supported
			case \User::ROLE_ADMIN:
				return false;
				break;
				
			case \User::ROLE_EDITOR:
				
				//Get the classroom
				list($code, $classroom) = $this->_classroom->get_entry($id, true);
				
				//If the mode of the classroom is in the list
				if($classroom[0]->mode == \Classroom::MODE_DRAFT or $classroom[0]->mode == \Classroom::MODE_REVIEW){
					return true;
				}
				
				return false;
				break;
				
			case \User::ROLE_TEACHER:
				
				//Find classroom by user id
				list($code, $classrooms) = $this->_classroom->find_by_user($user->id);
				
				//If we have results
				if(is_array($classrooms)){											
					
					//For each classroom user
					foreach ($classrooms as $classroom){
						
						//If the user is in the classroom
						if($classroom->id == $id){
							return true;
						}
					}
				}
				
				return false;
				break;
				
			case \User::ROLE_STUDENT:
				
				//Get the list of users associate to the classroom
				$classroom_users = $this->_classroom_user->find_users_ids_by_classroom($id);
				
				//If we have results
				if(is_array($classroom_users)){					
					
					//For each classroom user
					foreach ($classroom_users as $ids){
						
						//If the user is in the classroom
						if($ids == $user->id){
							return true;
						}
					}
				}
				
				return false;
				break;
		}
	}
	
	/**
	 * Get the resource associate to the route
	 * 
	 * @param string $route
	 * @return string
	 */
	public function getResourceFromRoute($route){		
		
		$route = explode(' ', $route);
		$route = explode('/', $route[1]);
		return  $route[0];
	}
}