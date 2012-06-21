from django.db import models
from django.contrib import auth

class Award(models.Model):
    title = models.CharField(max_length=100)
    description = models.TextField()
    icon_file = models.CharField(max_length=100)
    karma = models.IntegerField()
    
class Achievement(models.Model):
    '''An achievement represents a single instance of a user
    achieving a named award
    '''
    timestamp = models.DateTimeField("Date Achieved")
    award = models.OneToOneField(Award)
    
class KarmaWhoreUserProfile(models.Model):
    user = models.OneToOneField(auth.models.User)
    achievements = models.ForeignKey(Achievement)