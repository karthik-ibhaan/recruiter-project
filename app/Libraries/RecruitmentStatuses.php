<?php
    namespace App\Libraries;
    use App\Models\DemandModel;
    use App\Models\ClientModel;
    use CodeIgniter\Files\File;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    class RecruitmentStatuses {

        function recruitmentStatuses()
        {
            $recruitmentStatusesJSON = json_decode('{"00": ["00. Sourcing"], "01. Screening": ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent"], "02. Rejected-Sc": ["02. Duplicate", "02. Quest Duplicate", "02. Reject-Budget", "02. Reject-Fake", "02. Reject-HNP", "02. Reject-No Response", "02. Reject-Screen"], "03. Pos Cancelled": ["03. Pos. closed", "03. Pos. Hold", "03. Pos. Modified"], "04. Interview": ["04. F2F-In progress", "04. HR-In progress", "04. IG-Interview in Progress", "04. L1-In progress", "04. R1-In progress", "04. R2-In progress"], "05. Cand. Drop": ["05. Reject-Cand.  Dropped", "05. Reject-No Show"], "06. Rejected-Skill": ["06. Reject-Skill"], "07. Decision Pending": ["07. Feedback Pending"], "08. Selected": ["08. Documents Uploaded", "08. Offer Accepted", "08. Offer Pending", "08. Offer Released"], "09. Offer Cancelled": ["09. Candidate Dropped Offer", "09. Client Rejected", "09. Offer Declined"], "10. Joined": ["10. Joined"], "11. Resigned": ["11. Resigned"]}', true);
            return $recruitmentStatusesJSON;
        }

        function recruitmentStatuses07()
        {
            $recruitmentStatusesJSON = json_decode('{"00": ["00. Sourcing"], "01. Screening": ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent"], "02. Rejected-Sc": ["02. Duplicate", "02. Quest Duplicate", "02. Reject-Budget", "02. Reject-Fake", "02. Reject-HNP", "02. Reject-No Response", "02. Reject-Screen"], "03. Pos Cancelled": ["03. Pos. closed", "03. Pos. Hold", "03. Pos. Modified"], "04. Interview": ["04. F2F-In progress", "04. HR-In progress", "04. IG-Interview in Progress", "04. L1-In progress", "04. R1-In progress", "04. R2-In progress"], "05. Cand. Drop": ["05. Reject-Cand.  Dropped", "05. Reject-No Show"], "06. Rejected-Skill": ["06. Reject-Skill"], "07. Decision Pending": ["07. Feedback Pending"]}', true);
            return $recruitmentStatusesJSON;
        }
        
        function selectionStatuses()
        {
            $recruitmentStatusesJSON = json_decode('{"08. Selected": ["08. Documents Uploaded", "08. Offer Accepted", "08. Offer Pending", "08. Offer Released"], "09. Offer Cancelled": ["09. Candidate Dropped Offer", "09. Client Rejected", "09. Offer Declined"], "10. Joined": ["10. Joined"], "11. Resigned": ["11. Resigned"]}', true);
            return $recruitmentStatusesJSON;
        }
        function feedbackPendingStatuses()
        {
            $feedbackPending = ["01. CV sent to client", "01. No Feedback from client", "01. Profile Sent" ,"07. Feedback Pending"];
            return $feedbackPending;
        }

        function feedbackPendingData()
        {
            $clientModel = new ClientModel();
            $db = \Config\Database::connect();
            $clientModel = new ClientModel();
            $clients = $clientModel->orderBy('client_id', 'DESC')->findColumn('client_id');
            $clientName = $clientModel->orderBy('client_id','DESC')->findColumn('client_name');
            $feedbackPending = RecruitmentStatuses::feedbackPendingStatuses();
            $data = [];
            foreach($clients as $key => $value)
            {
                $builder = $db->table('candidates');
                $builder->select('client.CLIENT_NAME, demand.DEMAND_ID, candidates.CANDIDATE_ID, my_users.FULL_NAME, candidates.SUBMISSION_DATE');
                $builder->join('demand', 'demand.DEMAND_ID = candidates.DEMAND_ID');
                $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
                $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
                $builder->where('client.CLIENT_ID', $value);
                $builder->where('my_users.LEVEL>=',1);
                $builder->whereIn('candidates.RECRUITMENT_STATUS', $feedbackPending);
                $candidates = $builder->get()->getResultArray();
                $data[$clientName[$key]] = $candidates;
            }
            $details = array($clients, $clientName, $data);
            return $details;
        }
        function feedbackPendingTotal()
        {
            $session = session();
            $db = \Config\Database::connect();
            $feedbackPending = RecruitmentStatuses::feedbackPendingStatuses();
            if($session->get('level') >= "1")
            {
                $feedbackCount = $db->table('candidates');
                $feedbackCount->select('recruitment_status');
                $feedbackCount->join('my_users', 'my_users.USER_ID = candidates.RECRUITER');
                $feedbackCount->where('my_users.LEVEL>=', 1);
                $feedbackCount->whereIn('recruitment_status', $feedbackPending);
    
                if($session->get('level') == "3" || $session->get('level') == "2")
                {
                    $feedbackCount->where('recruiter',$session->get('user_id'));
                }
                $feedbackCount = $feedbackCount->countAllResults();
                return $feedbackCount;    
            }
            else
            {

            }
        }

        function candidatesTotalMonthly($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-01 00:00:00");
            }
            $session = session();
            $db = \Config\Database::connect();
            $recruitmentStatus = RecruitmentStatuses::recruitmentStatuses();
            $tz = "Asia/Kolkata";
            $curMonth = new \DateTime($date, new \DateTimeZone($tz));
            $nextMonth = new \DateTime($curMonth->format("Y-m-01 00:00:00"), new \DateTimeZone($tz));
            //Candidates Total Count
            $builder4 = $db->table('candidates');
            $builder4->select('candidate_name');
            $builder4->where('submission_date>=',$curMonth->format("Y-m-d 00:00:00"));
            $builder4->where('submission_date<',$nextMonth->modify("+1 month")->format("Y-m-01 00:00:00"));
            if($session->get('level') == "3" || $session->get('level') == "2")
            {
                $builder4->where('recruiter', $session->get('user_id'));
            }
            $candidatesTotal = $builder4->countAllResults();
            return $candidatesTotal;
        }

        function recruitmentStatusMonthly($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-01 00:00:00");
            }
            $session = session();
            $db = \Config\Database::connect();
            $recruitmentStatus = RecruitmentStatuses::recruitmentStatuses07();
            $selectionStatus = RecruitmentStatuses::selectionStatuses();
            $tz = "Asia/Kolkata";
            $curMonth = new \DateTime($date, new \DateTimeZone($tz));
            $nextMonth = new \DateTime($curMonth->format("Y-m-01 00:00:00"), new \DateTimeZone($tz));
            $nextMonth = $nextMonth->modify("+1 month");
            foreach($recruitmentStatus as $r=>$status)
            {
                $builder = $db->table('candidates');
                $builder->select('recruitment_status');
                $builder->whereIn('recruitment_status', $status);
                $builder->where('submission_date>=',$curMonth->format('Y-m-01 00:00:00'));
                $builder->where('submission_date<',$nextMonth->format("Y-m-01 00:00:00"));
                if($session->get('level') == "3" || $session->get('level') == "2")
                {
                    $builder->where('recruiter',$session->get('user_id'));
                }
                $queryResult = $builder->countAllResults();
                $candidateQueries[$r] = $queryResult;
            }
            
            foreach($selectionStatus as $s=>$status)
            {
                $statusCat = explode(".", $s);
                $builder = $db->table('candidates');
                $builder->select('recruitment_status');
                $builder->whereIn('recruitment_status', $status);
                if($statusCat[0] = "08")
                {
                    $builder->where('selection_date>=',$curMonth->format('Y-m-01 00:00:00'));
                    $builder->where('selection_date<',$nextMonth->format("Y-m-01 00:00:00"));
                }
                else if($statusCat[0] == "09")
                {
                    $builder->where('selection_date>=',$curMonth->format('Y-m-01 00:00:00'));
                    $builder->where('selection_date<',$nextMonth->format("Y-m-01 00:00:00"));
                }
                else if($statusCat[0] == "10")
                {
                    $builder->where('actual_doj>=',$curMonth->format('Y-m-01 00:00:00'));
                    $builder->where('actual_doj<',$nextMonth->format("Y-m-01 00:00:00"));
                }
                else if($statusCat[0] == "11")
                {
                    $builder->where('exit_date>=',$curMonth->format('Y-m-01 00:00:00'));
                    $builder->where('exit_date<',$nextMonth->format("Y-m-01 00:00:00"));
                }
                if($session->get('level') == "3" || $session->get('level') == "2")
                {
                    $builder->where('recruiter',$session->get('user_id'));
                }
                $queryResult = $builder->countAllResults();
                $candidateQueries[$s] = $queryResult;
            }
            return $candidateQueries;

        }

        function recruitmentStatusesDemands($demandID)
        {
            $db = \Config\Database::connect();
            $recruitmentStatus = RecruitmentStatuses::recruitmentStatuses();
            $selectionArray = array_merge($recruitmentStatus['08. Selected'], $recruitmentStatus['09. Offer Cancelled'], $recruitmentStatus['10. Joined'], $recruitmentStatus['11. Resigned']);
            $selectedArray = array_merge($recruitmentStatus['08. Selected'], $recruitmentStatus['10. Joined']);
            $dropoutArray = array_merge($recruitmentStatus["02. Rejected-Sc"], $recruitmentStatus["03. Pos Cancelled"],$recruitmentStatus["05. Cand. Drop"],$recruitmentStatus["06. Rejected-Skill"],$recruitmentStatus["09. Offer Cancelled"]);
            $builder = $db->table('candidates');
            $builder->where('DEMAND_ID',$demandID);
            $profilesShared = $builder->countAllResults();
            $builder2 = $db->table('candidates');
            $builder2->where('DEMAND_ID',$demandID);
            $builder2->where('interview_date!=',"");
            $interviewsScheduled = $builder2->countAllResults();
            $builder3 = $db->table('candidates');
            $builder3->where('DEMAND_ID',$demandID);
            $builder3->whereIn('RECRUITMENT_STATUS', $selectionArray);
            $candidatesSelected = $builder3->countAllResults();
            $builder4 = $db->table('candidates');
            $builder4->where('DEMAND_ID',$demandID);
            $builder4->whereIn('RECRUITMENT_STATUS',$dropoutArray);
            $candidatesRejected = $builder4->countAllResults();
            $builder5 = $db->table('candidates');
            $builder5->where('DEMAND_ID',$demandID);
            $builder5->whereIn('RECRUITMENT_STATUS',$recruitmentStatus['04. Interview']);
            $candidatesShortlisted = $builder5->countAllResults();

            $details = array($profilesShared, $interviewsScheduled, $candidatesSelected, $candidatesRejected, $candidatesShortlisted);
            return $details;
        }

        function candidateSentDaily($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-d 00:00:00", strtotime("today"));
            }
            $tz = "Asia/Kolkata";
            $curDate = new \DateTime($date, new \DateTimeZone($tz));
            $nextDate = new \DateTime($curDate->format("Y-m-d 00:00:00"), new \DateTimeZone($tz));
            $nextDate = $nextDate->modify("+1 day");
            $db = \Config\Database::connect();
            $session = session();

            //Candidates Sent To Client Today
            $candidateSent = $db->table('candidates');
            $candidateSent->select('candidate_id');
            $candidateSent->whereNotIn('recruitment_status',['00. Sourcing','01. Profile Sent']);
            $candidateSent->where('submission_date>=', $curDate->format("Y-m-d 00:00:00"));
            $candidateSent->where('submission_date<', $nextDate->format("Y-m-d 00:00:00"));
            if($session->get('level') == "3" || $session->get('level') == "2")
            {
                $candidateSent->where('recruiter', $session->get('user_id'));
            }
            $candidateSentToday = $candidateSent->countAllResults();
            return $candidateSentToday;
        }

        function candidatesTotalDaily($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-d 00:00:00", strtotime("today"));
            }
            $tz = "Asia/Kolkata";
            $curDate = new \DateTime($date, new \DateTimeZone($tz));
            $nextDate = new \DateTime($curDate->format("Y-m-d 00:00:00"), new \DateTimeZone($tz));
            $nextDate = $nextDate->modify("+1 day");
            $db = \Config\Database::connect();
            $session = session();

            $total = $db->table('candidates');
            $total->select('candidate_id');
            $total->whereNotIn('recruitment_status', ['00. Sourcing']);
            $total->where('submission_date>=',$curDate->format("Y-m-d 00:00:00"));
            $total->where('submission_date<',$nextDate->format("Y-m-d 00:00:00"));

            if($session->get('level') == "3" || $session->get('level') == "2")
            {
                $total->where('recruiter', $session->get('user_id'));
            }
            $candidatesTotalToday = $total->countAllResults();

            return $candidatesTotalToday;
        }

        function interviewScheduledDaily($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-d 00:00:00", strtotime("today"));
            }
            $session = session();
            $db = \Config\Database::connect();
            $tz = "Asia/Kolkata";
            $datetime = new \DateTime($date, new \DateTimeZone($tz));

            $datetime2 = new \DateTime($date, new \DateTimeZone($tz));
            $datetime2->modify('+1 day');

            $interviews = $db->table('candidates');
            $interviews->select('interview_date');
            $interviews->where('interview_date>=',$datetime->format('Y-m-d 00:00:00'));
            $interviews->where('interview_date<',$datetime2->format('Y-m-d 00:00:00'));
            if($session->get('level') == "2" || $session->get('level') == "3")
            {
                $interviews->where('recruiter',$session->get('user_id'));
            }
            $interviews = $interviews->countAllResults();

            return $interviews;
        }

        function demandsWorkedDaily($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-d 00:00:00", strtotime("today"));
            }
            $session = session();
            $db = \Config\Database::connect();
            $tz = "Asia/Kolkata";
            $datetime = new \DateTime($date, new \DateTimeZone($tz)); 

            $datetime2 = new \DateTime($date, new \DateTimeZone($tz));
            $datetime2->modify('+1 day');

            $demands = $db->table('candidates');
            $demands->select('demand_id');
            $demands->where('submission_date>=',$datetime->format("Y-m-d 00:00:00"));
            $demands->where('submission_date<',$datetime2->format("Y-m-d 00:00:00"));

            if($session->get('level') == "3" || $session->get('level') == "2")
            {
                $demands->where('recruiter', $session->get('user_id'));
            }
            $demands->distinct();
            $demandsTotal = $demands->countAllResults();

            return $demandsTotal;
        }

        function selectionData()
        {
            $db = \Config\Database::connect();
            $recruitmentStatus = RecruitmentStatuses::recruitmentStatuses();
            $selectionArray = array_merge($recruitmentStatus['08. Selected'], $recruitmentStatus['09. Offer Cancelled'], $recruitmentStatus['10. Joined'], $recruitmentStatus['11. Resigned']);
            $selections = $db->table('candidates');
            $selections->select('candidates.RECRUITMENT_STATUS, candidates.SELECTION_DATE, candidates.PLANNED_DOJ, candidates.ACTUAL_DOJ');
            $selections->whereIn('candidates.RECRUITMENT_STATUS', $selectionArray);
            $selections = $selections->get()->getResultArray();
            return $selections;
        }

        function interviewScheduledArray($date = null)
        {
            if(!(bool)$date)
            {
                $date = date("Y-m-d 00:00:00", strtotime("today"));
            }
            $interview = [];
            $session = session();
            $tz = 'Asia/Kolkata';
            $db = \Config\Database::connect();
            $interviewDate = new \DateTime($date, new \DateTimeZone($tz));
            $interviewDate2 = new \DateTime($date, new \DateTimeZone($tz));
            $interviewDate2->modify('+5 day');
            if($session->get('level') >="1")
            {
                $builder = $db->table('candidates');
                $builder->select('client.CLIENT_NAME, candidates.RECRUITER, my_users.FULL_NAME, candidates.CANDIDATE_NAME, demand.JOB_TITLE, candidates.WORK_LOCATION, demand.CUS_SPOC, candidates.PHONE_NO, candidates.EMAIL_ADDRESS, candidates.INTERVIEW_DATE, candidates.RECRUITMENT_STATUS');
                $builder->join('my_users','my_users.USER_ID = candidates.RECRUITER');
                $builder->join('demand','demand.DEMAND_ID = candidates.DEMAND_ID');
                $builder->join('client', 'client.CLIENT_ID = demand.CLIENT_ID');
                $builder->where('candidates.INTERVIEW_DATE>=',$interviewDate->format('Y-m-d 00:00:00'));
                $builder->where('candidates.INTERVIEW_DATE<',$interviewDate2->format('Y-m-d 00:00:00'));

                if($session->get('level') == "3")
                {
                    $builder->where('candidates.RECRUITER',$session->get('user_id'));
                }
                $interview = $builder->get()->getResultArray();
                $fieldNames = $builder->get()->getFieldNames();
                foreach($interview as $keys=>$data2)
                {
                    foreach($data2 as $keys2=>$value)
                    {
                        if($keys2 == "INTERVIEW_DATE")
                        {
                            $interviewDate3 = new \DateTime($value, new \DateTimeZone($tz));
                            $interview[$keys]['TIME'] = $interviewDate3->format('H:i:s');
                        }
                        if($keys2 == "PHONE_NO")
                        {
                            $value = json_decode($value);
                            if($value[1] != "")
                            {
                                $interview[$keys][$keys2] = $value[0].", ".$value[1];
                            }
                            else
                            {
                                $interview[$keys][$keys2] = $value[0];
                            }
                        }
                    }
                }
            }
            return $interview;
        }
    }
?>