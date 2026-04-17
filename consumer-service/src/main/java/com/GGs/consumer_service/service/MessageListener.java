package com.GGs.consumer_service.service;

import com.GGs.consumer_service.entity.Message;
import com.GGs.consumer_service.repository.MessageRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.kafka.annotation.KafkaListener;
import org.springframework.stereotype.Service;

@Service
public class MessageListener {
    
    @Autowired
    private MessageRepository messageRepository;
    
    @KafkaListener(topics = "my-topic", groupId = "my-group")
    public void listen(String messageContent) {
        System.out.println("Получено сообщение: " + messageContent);
        
        Message message = new Message(messageContent);
        messageRepository.save(message);
        
        System.out.println("Сообщение сохранено в БД!");
    }
}