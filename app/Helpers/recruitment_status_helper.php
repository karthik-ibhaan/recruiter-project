<?php

    if(!function_exists('recruitmentStatuses'))
    {
        function recruitmentStatuses()
        {
            $recruitmentStatusesJSON = json_decode('{"00": ["00. Sourcing"], "01. Screening": ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent"], "02. Rejected-Sc": ["02. Duplicate", "02. Quest Duplicate", "02. Reject-Budget", "02. Reject-Fake", "02. Reject-HNP", "02. Reject-No Response", "02. Reject-Screen"], "03. Pos Cancelled": ["03. Pos.  closed", "03. Pos.  Hold", "03. Pos.  Modified"], "04. Interview": ["04. F2F-In progress", "04. HR-In progress", "04. L1-In progress", "04. R1-In progress", "04. R2-In progress"], "05. Cand. Drop": ["05. Reject-Cand.  Dropped", "05. Reject-No Show"], "06. Rejected-Skill": ["06. Reject-Skill"], "07. Decision Pending": ["07. Feedback Pending"], "08. Selected": ["08. Documents Uploaded", "08. Offer Accepted", "08. Offer Pending", "08. Offer Released"], "09. Offer Cancelled": ["09. Candidate Dropped Offer", "09. Client Rejected", "09. Offer Declined"], "10. Joined": ["10. Joined"], "11. Resigned": ["11. Resigned"]}', true);
            return $recruitmentStatusesJSON;
        }
    }

    if(!function_exists('feedbackPendingTotal'))
    {
        function feedbackPendingTotal()
        {
            $session = session();
            $feedbackPending = ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent" ,"07. Feedback Pending"];
            if($session->get('level') == "3" || $session->get('level') == "2")
            {
                $feedbackCount = $db->table('candidates');
                $feedbackCount->select('recruitment_status');
                $feedbackCount->whereIn('recruitment_status', $feedbackPending);
                $feedbackCount->where('recruiter',$session->get('user_id'));
                $feedbackCount = $feedbackCount->countAllResults();
            }
            else
            {
                $feedbackCount = $db->table('candidates');
                $feedbackCount->join('my_users', 'my_users.USER_ID = candidates.RECRUITER');
                $feedbackCount->select('candidates.RECRUITMENT_STATUS');
                $feedbackCount->whereIn('candidates.RECRUITMENT_STATUS', $feedbackPending);
                $feedbackCount->where('level>', 1);
                $feedbackCount = $feedbackCount->countAllResults();
            }
            return $feedbackCount;
        }
    }
?>