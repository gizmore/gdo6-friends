<?php
namespace GDO\Friends\Method;

use GDO\Core\GDT_Hook;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Friends\FriendRequest;
use GDO\Friends\Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\UI\GDT_Link;
use GDO\User\GDT_User;
use GDO\User\User;
use GDO\Form\GDT_Validator;

final class Request extends MethodForm
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function createForm(GDT_Form $form)
	{
		$gdo = FriendRequest::table();
		$form->addFields(array(
			GDT_User::make('frq_friend')->notNull(),
		    GDT_Validator::make()->validator('frq_friend', [$this, 'validate_NoRelation']),
			$gdo->gdoColumn('frq_relation'),
			GDT_Submit::make(),
			GDT_AntiCSRF::make(),
		));
	}
	
	public function execute()
	{
		$response = parent::execute();
		return Module_Friends::instance()->renderTabs()->add($response);
	}
	
	public function validate_NoRelation(GDT_Form $form, GDT_User $field)
	{
		$user = User::current();
		$friend = $field->getUser();
		if ($friend->getID() === $user->getID())
		{
			return $field->error('err_friend_self');
		}
		if (Friendship::areRelated($user, $friend))
		{
			return $field->error('err_already_related', [$friend->displayNameLabel()]);
		}
		if ($request = FriendRequest::getPendingFor($user, $friend))
		{
			if ($request->isDenied())
			{
				return $field->error('err_already_pending_denied', [$friend->displayNameLabel()]);
			}
			else
			{
				return $field->error('err_already_pending', [$friend->displayNameLabel()]);
			}
		}
		return true;
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = User::current();
		$request = FriendRequest::blank($form->getFormData())->setVar('frq_user', $user->getID())->insert();
		
		$this->sendMail($request);
		
		GDT_Hook::call('FriendsRequest', $request);
		
		return $this->message('msg_friend_request_sent');
	}
	
	private function sendMail(FriendRequest $request)
	{
		$mail = new Mail();
		$mail->setSender(GWF_BOT_EMAIL);
		$mail->setSenderName(GWF_BOT_NAME);
		
		$friend = $request->getFriend();
		$user = $request->getUser();
		$relation = GDT_FriendRelation::displayRelation($request->getRelation());
		$sitename = sitename();
		$append = "&from={$user->getID()}&for={$friend->getID()}&token={$request->gdoHashcode()}";
		$linkAccept = GDT_Link::anchor(url('Friends', 'Accept', $append));
		$linkDeny = GDT_Link::anchor(url('Friends', 'Deny', $append));
		
		$mail->setSubject(tusr($friend, 'mail_subj_friend_request', [$sitename, $user->displayNameLabel()]));
		$args = [$friend->displayNameLabel(), $user->displayNameLabel(), $relation, $sitename, $linkAccept, $linkDeny];
		$mail->setBody(tusr($friend, 'mail_body_friend_request', $args));
		
		$mail->sendToUser($friend);
	}
}
