<?php

namespace App\Serializer;

class SerializerGroups
{
    /** Common Group Tags */
    const PRIVATE = "private";
    const PUBLIC = "public";
    /** */



    /** Special Group Tags */
    const AFTER_LOGIN = "after_login";
    /** */




    /** User Groups */
    const USER = "user";
    /** */


    /** Care place groups */
    const CARE_PLACE = "care_place";
    const ASSIGNMENT_CARE_PLACE = "assignment_care_place";
    const CARE_PLACE_ROOM = "care_place_room";
    /** */



    /**  Organization Groups */
    const ORGANIZATION = "organization";
    const ORGANIZATION_MEMBER = "organization_member";
    const ORGANIZATION_RELATIVE_MEMBER = "organization_member";
    const ORGANIZATION_INFORMATION = "organization_information";
    /** */


    /** Workspace */
    const WORKSPACE = "workspace";
    const WORKSPACE_MEMBER = "workspace_member";
    const NOTIFICATION_WORKSPACE_MEMBER = "notification_workspace_member";
    const CLIENT_MEMBER = "client_member";
    const SPENDING_MEMBER = 'spending_member';

    const STAFF_TASK_STATUS_WORKSPACE_MEMBER = "staff_task_status_workspace_member";
    const WORKSPACE_SHIFT = 'workspace_shift';
    /** */


    /** System */
    const PERIOD = "period";
    const CURRENCY = "currency";
    const WEEK_DAY = "week_day";
    const PAYMENT_METHOD = "payment_method";
    const DISEASE = 'disease';
    const DIAGNOSE = 'diagnose';
    /** */



    /** Address */
    const ADDRESS = "address";
    /** */



    /** Task */
    const TASK = "task";
    const TASK_TYPE = "task_type";
    const TASK_CODE = "task_code";
    const TASK_REQUIRED_TOOLS = "task_required_tools";
    const TASK_TAGS = "task_tags";
    const TASK_HOUR = "task_hours";
    const TASK_STATUS = "task_status";
    const TASK_STATUS_STAFF_TASK = "task_status";
    /** */




    /** Member Task Planning */
    const TASK_PLANNING = 'task_planning';
    const TASK_PLANNING_DAY = 'task_planning_day';
    const MEMBER_TASK = 'member_task';
    const MEMBER_TASK_SCHEDULES = 'member_task_schedules';
    /** */



    /** Staff Task Planning */
    const STAFF_TASK_PLANNING = 'task_planning';
    const STAFF_TASK = 'staff_task';
    const C_STAFF_TASK = 'c_staff_task';
    const STAFF_TASK_PLANNING_STATUS = 'staff_task_plan_status';
    const STAFF_TASK_TASK_PLANNING = 'staff_task_task_plan';
    const STAFF_TASK_STATUS_STAFF_TASK = 'staff_task_status_staff_task';
    /** */



    /** VisitGroup */
    const VISIT = 'visit';
    const VISIT_GROUP = 'visit_group';
    const VISIT_CLIENTS = 'visit_clients';
    /** */


    /** Visit */
    const SPENDING_VISIT = 'spending_visit';
    const VISIT_SPENDING = 'visit_spending';
    const VISIT_SPENDING_PLUGS = 'visit_spending_plugs';
    /** */


    /** VisitGroup */
    const HEALTH_INFORMATION = 'health_information';
    const MEDICATION = 'medication';
    /** */



    /** Client Member */
    const MEMBER_ALLERGY = 'member_allergy';
    /** */


    /** Care Contract */
    const CARE_CONTRACT_WORKSPACE_MEMBER = 'care_contract_workspace_member';
    const CARE_CONTRACT_DISEASES = 'care_contract_diseases';
    const CARE_CONTRACT_DIAGNOSES = 'care_contract_diagnoses';
    const CARE_CONTRACT_DOCTOR = 'care_contract_doctor';
    const CARE_CONTRACT = 'care_contract';
    /** */


    /** Client Type */
    const CLIENT_TYPE = 'client_type';
    const PRESCRIPTION_TYPE = 'prescription_type';
    /** */


    /** Datetime */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /** */



    /** Notification */
    const NOTIFICATION = 'notification';
    const NOTIFICATION_SETTING_TYPE = 'notification_setting_type';
    const NOTIFICATION_SETTINGS = 'notification_settings';
    const PUSH_NOTIFICATION = 'push_notification';
    const PUSH_NOTIFICATION_TYPE = 'push_notification_type';
    const WEB_NOTIFICATION_TYPE = 'web_notification_type';
    const WEB_NOTIFICATION = 'web_notification';
    /** */
}
