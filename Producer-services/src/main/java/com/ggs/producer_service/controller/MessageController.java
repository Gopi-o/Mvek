package com.ggs.producer_service.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.kafka.core.KafkaTemplate;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api")
public class MessageController {
    
    @Autowired
    private KafkaTemplate<String, String> kafkaTemplate;
    
    @PostMapping("/send")
    public String sendMessage(@RequestBody String message) {
        kafkaTemplate.send("my-topic", message);
        return "Сообщение отправлено: " + message;
    }
}