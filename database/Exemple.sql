
SELECT A.* FROM ACCOUNT A JOIN FRIEND F ON A.USER_ID = F.FRIEND_ID WHERE F.USER_ID = ?;
SELECT * FROM COMPETITOR  WHERE EVENT_ID = ?;
SELECT * FROM INFO WHERE EVENT_ID = ?;
SELECT E.* FROM EVENT E JOIN ACCOUNT A ON A.USER_ID = E.ORGANIZER WHERE A.USER_ID = ?;
SELECT * FROM EVENT WHERE ORGANIZER = ?;
