<?php
namespace GDO\Friends\Method;

use GDO\Core\GDT_Hook;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\UI\GDT_Link;
use GDO\User\GDT_User;
use GDO\User\GDO_User;
use GDO\Form\GDT_Validator;

final class Request extends MethodForm
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function createForm(GDT_Form $form)
	{
		$gdo = GDO_FriendRequest::table();
		$form->addFields(array(
			GDT_User::make('frq_friend')->notNull(),
			GDT_Validator::make()->validator('frq_friend', [$this, 'validate_NoRelation']),
			GDT_Validator::make()->validator('frq_friend', [$this, 'validate_CanRequest']),
		));
		if (Module_Friends::instance()->cfgRelations())
		{
			$form->addField($gdo->gdoColumn('frq_relation'));
		}
		$form->addFields(array(
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
		if ($friend = $field->getUser())
		{
			$user = GDO_User::current();
			if ($friend === $user)
			{
				return $field->error('err_friend_self');
			}
			if (GDO_Friendship::areRelated($user, $friend))
			{
				return $field->error('err_already_related', [$friend->displayNameLabel()]);
			}
			if ($request = GDO_FriendRequest::getPendingFor($user, $friend))
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
		}
		return true;
	}
	
	public function validate_canRequest(GDT_Form $form, GDT_User $field)
	{
		if ($user = $field->getValue())
		{
			$reason = '';
			if (!(Module_Friends::instance()->canRequest($user, $reason)))
			{
				return $field->error('err_requesting_denied', [$reason]);
			}
		}
		return true;
	}
	
	public function formValidated(GDT_Form $form)
	{
		$user = GDO_User::current();
		$data = $form->getFormData();
		if (!Module_Friends::instance()->cfgRelations())
		{
			$data['friend_relation'] = 'friends';
		}
		
		$request = GDO_FriendRequest::blank($data)->setVar('frq_user', $user->getID())->insert();
		
		$this->sendMail($request);
		
		GDT_Hook::callWithIPC('FriendsRequest', $request);
		
		return $this->message('msg_friend_request_sent');
	}
	
	private function sendMail(GDO_FriendRequest $request)
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
