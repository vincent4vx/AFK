ALTER TABLE FRIEND
ADD COLUMN FRIEND_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD PRIMARY KEY (USER1, USER2);

ALTER TABLE ACCOUNT
ADD COLUMN INSCRIPTION_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

CREATE OR REPLACE VIEW ACCOUNT_FLUX AS
SELECT 'JOIN' AS FLUX_TYPE, USER_ID AS SENDER, EVENT_ID AS TARGET, NULL AS FLUX_DATA, UNIX_TIMESTAMP(JOIN_DATE) AS FLUX_DATE FROM COMPETITOR
UNION
SELECT 'FRIEND' AS FLUX_TYPE, USER1 AS SENDER, USER2 AS TARGET, NULL AS FLUX_DATA, UNIX_TIMESTAMP(FRIEND_DATE) AS FLUX_DATE FROM FRIEND
UNION
SELECT 'FRIEND' AS FLUX_TYPE, USER2 AS SENDER, USER1 AS TARGET, NULL AS FLUX_DATA, UNIX_TIMESTAMP(FRIEND_DATE) AS FLUX_DATE FROM FRIEND
UNION 
SELECT 'EVENTCREATE' AS FLUX_TYPE, ORGANIZER AS SENDER, EVENT_ID AS TARGET, NULL AS FLUX_DATA, UNIX_TIMESTAMP(EVENT_DATE) AS FLUX_DATE FROM EVENT
UNION
SELECT 'INSCRIPTION' AS FLUX_TYPE, USER_ID AS SENDER, NULL AS TARGET, NULL AS FLUX_DATA, UNIX_TIMESTAMP(INSCRIPTION_DATE) AS FLUX_DATE FROM ACCOUNT
ORDER BY FLUX_DATE DESC;