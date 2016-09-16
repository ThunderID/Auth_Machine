FORMAT: 1A

# AUTHMACHINE

# Clients [/clients]
Client resource representation.

## Show all Clients [GET /clients]


+ Request (application/json)
    + Body

            {
                "search": {
                    "_id": "string",
                    "type": "mobile|web",
                    "version": "string",
                    "code": "string",
                    "grant": "string",
                    "scopes": "string"
                },
                "sort": {
                    "newest": "asc|desc",
                    "version": "desc|asc",
                    "type": "desc|asc",
                    "company": "desc|asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "_id": "string",
                        "app": {
                            "type": "string",
                            "version": "string",
                            "name": "string"
                        },
                        "company": {
                            "code": "string",
                            "name": "string"
                        },
                        "key": "string",
                        "secret": "string",
                        "grants": {
                            "name": "string",
                            "scopes": [
                                "string"
                            ]
                        },
                        "expire": {
                            "scheduled": {
                                "timezone": "string",
                                "hour": "integer"
                            },
                            "timeout": {
                                "minute": "integer"
                            }
                        }
                    },
                    "count": "integer"
                }
            }

## Store Client [POST /clients]


+ Request (application/json)
    + Body

            {
                "_id": null,
                "app": {
                    "type": "string",
                    "version": "string",
                    "name": "string"
                },
                "company": {
                    "code": "string",
                    "name": "string"
                },
                "key": "string",
                "secret": "string",
                "grants": {
                    "name": "string",
                    "scopes": [
                        "string"
                    ]
                },
                "expire": {
                    "scheduled": {
                        "timezone": "string",
                        "hour": "integer"
                    },
                    "timeout": {
                        "minute": "integer"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "app": {
                        "type": "string",
                        "version": "string",
                        "name": "string"
                    },
                    "company": {
                        "code": "string",
                        "name": "string"
                    },
                    "key": "string",
                    "secret": "string",
                    "grants": {
                        "name": "string",
                        "scopes": [
                            "string"
                        ]
                    },
                    "expire": {
                        "scheduled": {
                            "timezone": "string",
                            "hour": "integer"
                        },
                        "timeout": {
                            "minute": "integer"
                        }
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete Client [DELETE /clients]


+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": null,
                    "app": {
                        "type": "string",
                        "version": "string",
                        "name": "string"
                    },
                    "company": {
                        "code": "string",
                        "name": "string"
                    },
                    "key": "string",
                    "secret": "string",
                    "grants": {
                        "name": "string",
                        "scopes": [
                            "string"
                        ]
                    },
                    "expire": {
                        "scheduled": {
                            "timezone": "string",
                            "hour": "integer"
                        },
                        "timeout": {
                            "minute": "integer"
                        }
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

# Users [/users]
User resource representation.

## Show all Users [GET /users]


+ Request (application/json)
    + Body

            {
                "search": {
                    "_id": "string",
                    "type": "mobile|web",
                    "version": "string",
                    "code": "string",
                    "client": "string",
                    "scopes": "string"
                },
                "sort": {
                    "newest": "asc|desc",
                    "version": "desc|asc",
                    "type": "desc|asc",
                    "company": "desc|asc",
                    "name": "desc|asc"
                },
                "take": "integer",
                "skip": "integer"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "data": {
                        "_id": "string",
                        "email": "string",
                        "user": {
                            "name": "string"
                        },
                        "accesses": {
                            "client_id": "string",
                            "app": {
                                "type": "web|mobile",
                                "name": "string",
                                "version": "string"
                            },
                            "company": {
                                "code": "string",
                                "name": "string"
                            },
                            "scopes": [
                                "string"
                            ]
                        },
                        "expire": {
                            "scheduled": {
                                "timezone": "string",
                                "hour": "integer"
                            },
                            "timeout": {
                                "minute": "integer"
                            }
                        }
                    },
                    "count": "integer"
                }
            }

## Store User [POST /users]


+ Request (application/json)
    + Body

            {
                "_id": "null",
                "email": "string",
                "password": "string",
                "user": {
                    "name": "string"
                },
                "accesses": {
                    "client_id": "string",
                    "app": {
                        "type": "web|mobile",
                        "name": "string",
                        "version": "string"
                    },
                    "company": {
                        "code": "string",
                        "name": "string"
                    },
                    "scopes": [
                        "string"
                    ]
                },
                "expire": {
                    "scheduled": {
                        "timezone": "string",
                        "hour": "integer"
                    },
                    "timeout": {
                        "minute": "integer"
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "email": "string",
                    "user": {
                        "name": "string"
                    },
                    "accesses": {
                        "client_id": "string",
                        "app": {
                            "type": "web|mobile",
                            "name": "string",
                            "version": "string"
                        },
                        "company": {
                            "code": "string",
                            "name": "string"
                        },
                        "scopes": [
                            "string"
                        ]
                    },
                    "expire": {
                        "scheduled": {
                            "timezone": "string",
                            "hour": "integer"
                        },
                        "timeout": {
                            "minute": "integer"
                        }
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

## Delete User [DELETE /users]


+ Request (application/json)
    + Body

            {
                "id": null
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "_id": "string",
                    "email": "string",
                    "user": {
                        "name": "string"
                    },
                    "accesses": {
                        "client_id": "string",
                        "app": {
                            "type": "web|mobile",
                            "name": "string",
                            "version": "string"
                        },
                        "company": {
                            "code": "string",
                            "name": "string"
                        },
                        "scopes": [
                            "string"
                        ]
                    },
                    "expire": {
                        "scheduled": {
                            "timezone": "string",
                            "hour": "integer"
                        },
                        "timeout": {
                            "minute": "integer"
                        }
                    }
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "code must be unique."
                    ]
                }
            }

# Tokens [/tokens]
Auth resource representation.

## Generate token [POST /tokens/generate]


+ Request (application/json)
    + Body

            {
                "key": "string",
                "secret": "string",
                "grant": "string",
                "email": "string",
                "password": "string"
            }

+ Response 200 (application/json)
    + Body

            {
                "status": "success",
                "data": {
                    "token": {
                        "header": [
                            "alg",
                            "typ"
                        ]
                    },
                    "0": {
                        "payload": {
                            "0": "iss",
                            "1": "exp",
                            "content": [
                                "company",
                                "scopes",
                                "application",
                                "user",
                                "client_id"
                            ]
                        }
                    },
                    "1": "verify signature"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "status": {
                    "error": [
                        "password required."
                    ]
                }
            }