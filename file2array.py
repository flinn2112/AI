#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Fri May  3 07:21:19 2019

@author: frankkempf
"""

import numpy as np
import time
# 1 million samples
n_samples=1000000
# Write random floating point numbers as string on a local CSV file
with open('fdata.txt', 'w') as fdata:
    for _ in range(n_samples):
        fdata.write(str(10*np.random.random())+',')
# Read the CSV in a list, convert to ndarray (reshape just for fun) and time it
t1=time.time()
with open('fdata.txt','r') as fdata:
    datastr=fdata.read()
lst = datastr.split(',')
lst.pop()
array_lst=np.array(lst,dtype=float).reshape(1000,1000)
t2=time.time()
print(array_lst)
print('\nShape: ',array_lst.shape)
print(f"Time took to read: {t2-t1} seconds.")