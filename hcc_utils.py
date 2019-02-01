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
    m_strPath      = ''    
    m_tData        = None
    m_Data         = None
    def __init__(self, strFilename):
        self.m_strPath = strFilename        
        print('File is ' + self.m_strPath) 
    def setData(self, data):
        self.m_Data = data
    def load(self):
        print('Loading' + self.m_strPath) 
        self.m_tData = loadtxt(self.m_strPath)
        
    def save(self, what, strFormat, cDelimiter, cNewLine):
        savetxt(self.m_strPath, what, fmt=strFormat, delimiter=cDelimiter, newline=cNewLine)
    def saveData(self, strFormat, cDelimiter, cNewLine):
        print('Saving----> ' + self.m_strPath) 
        if cNewLine == None:
            savetxt(self.m_strPath, self.m_Data, fmt=strFormat, delimiter=' ')
        else:
            savetxt(self.m_strPath, self.m_Data, fmt=strFormat, delimiter=' ', newline=cNewLine)
#        savetxt(self.m_strPath, self.m_Data, fmt=strFormat, delimiter=cDelimiter, newline=cNewLine)
    
    @staticmethod  
    def savex(strPath, what, strFormat, cDelimiter, cNewLine):
        savetxt(strPath, what, fmt=strFormat, delimiter=cDelimiter, newline=cNewLine)
        
        

    