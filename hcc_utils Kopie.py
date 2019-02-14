#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Wed Dec 26 12:54:23 2018

@author: frankkempf
"""
import tensorflow as tf  
from numpy import loadtxt, savetxt, reshape 
import os
os.environ['TF_CPP_MIN_LOG_LEVEL']='2'

def makeHey():
    print("Hey!")
    
class acmeEnv:
    m_fileIn = ''
    
    m_tData        = None
   
    def __init__(self, strFilename):
        self.m_fileIn = strFilename
        
        print('File is ' + self.m_fileIn) 
        
    def load(self):
        print('Loading' + self.m_fileIn) 
        self.m_tData = loadtxt(self.m_fileIn)
        
        

    