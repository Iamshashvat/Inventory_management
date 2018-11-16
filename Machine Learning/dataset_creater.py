import numpy as np
import matplotlib.pyplot as plt
import pandas as pd

# Importing the dataset
dataset = pd.read_csv('Out.csv')

import string
print(string.letters)
letters=['A','B','C']


import random
random.choice(letters)


random.randint(1,11)
for i in dataset.index:
    #dataset.at[i, 'inventory_id'] =random.choice(letters)
    dataset.at[i, 'quantity'] =random.randint(150,250)
    dataset.at[i, 'price'] =random.randint(300,380)
    dataset.at[i, 'discount'] = random.randint(0,30)
    
    
#dataset=dataset.drop(['sales'],axis=1)    

dataset.to_csv('Laptop.csv')

dataset12 = pd.read_csv('Out.csv')

dataset=dataset.drop(['INDEX'],axis=0)    