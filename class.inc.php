<?php
    class homepage{
        public $prj_name;
        public $firstname;
        public $lastname;
        public $prj_description;
        public $prj_endDate;
        public $requestedFund;
        public $prjUser;
        public $prjId;

        public function __construct($prj_name,$firstname,$lastname,$prj_description,$prj_endDate,$requestedFund,$prjUser,$prjId)
        {
            $this->prj_name = $prj_name;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->prj_description = $prj_description;
            $this->prj_endDate = $prj_endDate;
            $this->requestedFund = $requestedFund;
            $this->prjUser = $prjUser;
            $this->prjId = $prjId;
        }
    }

    ?>