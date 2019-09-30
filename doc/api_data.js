define({ "api": [
  {
    "type": "post",
    "url": "/order-tickets",
    "title": "Create",
    "name": "actionCreate",
    "group": "OrderTicket",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>Order ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ticket_id",
            "description": "<p>Ticket ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>OrderTicket unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>Order ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "ticket_id",
            "description": "<p>Ticket ID.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "\nHTTP/1.1 200 OK\n{\n    \"id\": \"5\",\n    \"order_id\": \"2\",\n    \"ticket_id\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderClosed",
            "description": "<p>Order by this order_id is closed.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderNotFound",
            "description": "<p>Order by this order_id not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderBelongsToAnotherClient",
            "description": "<p>This order belongs to another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketNotFound",
            "description": "<p>Not found ticket by ticket_id.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketNotAvailable",
            "description": "<p>This ticket is not available.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketReserved",
            "description": "<p>This ticket is reserved.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketExpired",
            "description": "<p>This ticket has expired.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketBlockedByAnotherClient",
            "description": "<p>This ticket has blocked by another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketBelongsToAnotherClient",
            "description": "<p>This order ticket belongs to another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketDataValidationFailed",
            "description": "<p>Data are not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "    HTTP/1.1 400 Bad Request\n    {\n        \"error\": \"OrderClosed\"\n    }\nor\n    HTTP/1.1 400 Bad Request\n    {\n        \"error\": \"OrderTicketDataValidationFailed\"\n        \"description\": \"Order ID is invalid.\"\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderTicketController.php",
    "groupTitle": "OrderTicket"
  },
  {
    "type": "delete",
    "url": "/order-tickets/:id",
    "title": "Delete",
    "name": "actionDelete",
    "group": "OrderTicket",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>OrderTicket unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>OrderTicket unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>Order ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "ticket_id",
            "description": "<p>Ticket ID.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "\nHTTP/1.1 200 OK\n{\n    \"id\": \"5\",\n    \"order_id\": \"2\",\n    \"ticket_id\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketNotFound",
            "description": "<p>Bad order ticket identifier.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketBelongsToAnotherClient",
            "description": "<p>This order ticket belongs to another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketNotFound",
            "description": "<p>Not found ticket by ticket_id.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketNotAvailable",
            "description": "<p>This ticket is not available.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketReserved",
            "description": "<p>This ticket is reserved.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketExpired",
            "description": "<p>This ticket has expired.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "TicketBlockedByAnotherClient",
            "description": "<p>This ticket has blocked by another client.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"error\": \"OrderTicketNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderTicketController.php",
    "groupTitle": "OrderTicket"
  },
  {
    "type": "get",
    "url": "/order-tickets",
    "title": "Elements list",
    "name": "actionIndex",
    "group": "OrderTicket",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "OrderTicket[]",
            "optional": false,
            "field": "array",
            "description": "<p>List of order-tickets.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "    HTTP/1.1 200 OK\n    [\n         {\n             \"id\": \"5\",\n             \"order_id\": \"2\",\n             \"ticket_id\": \"2\",\n         },\n         ...\n    ]\n\nor\n    HTTP/1.1 200 OK\n    []",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderTicketController.php",
    "groupTitle": "OrderTicket"
  },
  {
    "type": "get",
    "url": "/order-tickets/:id",
    "title": "Details",
    "name": "actionView",
    "group": "OrderTicket",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>OrderTicket unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>OrderTicket unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>Order ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "ticket_id",
            "description": "<p>Ticket ID.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"id\": \"5\",\n    \"order_id\": \"2\",\n    \"ticket_id\": \"2\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketNotFound",
            "description": "<p>Bad order ticket identifier.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderTicketBelongsToAnotherClient",
            "description": "<p>This order ticket belongs to another client.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"error\": \"OrderTicketNotFound\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderTicketController.php",
    "groupTitle": "OrderTicket"
  },
  {
    "type": "get",
    "url": "/orders/:id/buy",
    "title": "Buy",
    "name": "actionBuy",
    "group": "Order",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "client_id",
            "description": "<p>Client ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Order status .</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"id\": \"1\",\n    \"client_id\": \"1\",\n    \"status\": \"open\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderClosed",
            "description": "<p>Order by this order_id is closed.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderNotFound",
            "description": "<p>Order by this order_id not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderBelongsToAnotherClient",
            "description": "<p>This order belongs to another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderWithoutTickets",
            "description": "<p>Order without tickets.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "PaymentFailed",
            "description": "<p>Payment failed.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "    HTTP/1.1 400 Bad Request\n    {\n        \"error\": \"OrderClosed\"\n    }\nor\n    HTTP/1.1 402 Payment Required\n    {\n        \"error\": \"PaymentFailed\"\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "type": "post",
    "url": "/orders",
    "title": "Create",
    "name": "actionCreate",
    "group": "Order",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "client_id",
            "description": "<p>Client ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Order status .</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"id\": \"1\",\n    \"client_id\": \"1\",\n    \"status\": \"open\",\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "type": "get",
    "url": "/orders/:id/reserve",
    "title": "Reserve",
    "name": "actionReserve",
    "group": "Order",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "client_id",
            "description": "<p>Client ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Order status .</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"id\": \"1\",\n    \"client_id\": \"1\",\n    \"status\": \"open\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderClosed",
            "description": "<p>Order by this order_id is closed.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderNotFound",
            "description": "<p>Order by this order_id not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderBelongsToAnotherClient",
            "description": "<p>This order belongs to another client.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderWithoutTickets",
            "description": "<p>Order without tickets.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "PaymentFailed",
            "description": "<p>Payment failed.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "    HTTP/1.1 400 Bad Request\n    {\n        \"error\": \"OrderClosed\"\n    }\nor\n    HTTP/1.1 402 Payment Required\n    {\n        \"error\": \"PaymentFailed\"\n    }",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "type": "get",
    "url": "/orders/:id",
    "title": "Details",
    "name": "actionView",
    "group": "Order",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Order unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "client_id",
            "description": "<p>Client ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Order status .</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n    \"id\": \"1\",\n    \"client_id\": \"1\",\n    \"status\": \"open\",\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderClosed",
            "description": "<p>Order by this order_id is closed.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderNotFound",
            "description": "<p>Order by this order_id not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "OrderBelongsToAnotherClient",
            "description": "<p>This order belongs to another client.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request\n{\n    \"error\": \"OrderClosed\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/OrderController.php",
    "groupTitle": "Order"
  },
  {
    "type": "get",
    "url": "/tickets",
    "title": "Tickets list",
    "name": "actionIndex",
    "group": "Ticket",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Ticket[]",
            "optional": false,
            "field": "tickets",
            "description": "<p>Tickets list.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "    HTTP/1.1 200 OK\n    [\n        {\n            \"id\": \"1\",\n            \"column\": \"1\",\n            \"row\": \"1\",\n            \"amount\": \"58\",\n            \"status\": \"open\",\n            \"expired_at\": \"1569527422\",\n            \"block_expired_at\": \"1569016369\",\n            \"orderTicket\": null,\n            \"order\": null\n        },\n        ...\n    ]\nor\n    HTTP/1.1 200 OK\n    []",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "api/controllers/TicketController.php",
    "groupTitle": "Ticket"
  }
] });
