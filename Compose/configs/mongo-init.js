db.createUser({
    user: 'phpmongo',
    pwd: 'pMpD~16131604',
    roles: [
        {
            role: 'dbOwner',
            db: 'pmpdb'
        }
    ]
});
