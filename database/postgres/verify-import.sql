-- Compare this result to the MariaDB source result before any Vercel deploy.
SELECT 'tbl_user' AS table_name, count(*) AS row_count FROM tbl_user
UNION ALL SELECT 'tbl_org_unit', count(*) FROM tbl_org_unit
UNION ALL SELECT 'tbl_project', count(*) FROM tbl_project
UNION ALL SELECT 'tbl_project_file', count(*) FROM tbl_project_file
UNION ALL SELECT 'tbl_project_member', count(*) FROM tbl_project_member
UNION ALL SELECT 'tbl_project_payment', count(*) FROM tbl_project_payment
UNION ALL SELECT 'tbl_project_task', count(*) FROM tbl_project_task
UNION ALL SELECT 'tbl_scientific_curriculum_vitae', count(*) FROM tbl_scientific_curriculum_vitae
UNION ALL SELECT 'tbl_sidebar', count(*) FROM tbl_sidebar
ORDER BY table_name;
